#include <ArduinoJson.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>
#include <MySQL_Encrypt_Sha1.h>
#include <MySQL_Packet.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>



const char* ssid = "INEA-137828";
const char* password = "ALCLF4A0A9FB";
//const char* ssid = "HUAWEI-E5186-D603";
//const char* password = "TNE9424QNR0";
const char* host = "www.kornelb.com.pl"; // domena
String path = "/on_off.json";
const int pin = 14;
char devices_id[5] = "2604";
char devices_type[10] = "control";
WiFiServer server(80);
//IPAddress ip(192, 168, 8, 101);
IPAddress ip(192, 168, 1, 9);
IPAddress gateway(192, 168, 1, 1);
//IPAddress gateway(192, 168, 8, 1);
IPAddress subnet(255, 255, 255, 0);
WiFiClient client;
 
MySQL_Connection conn((Client *)&client);
//zapytania do bazy
char INSERT[] = "REPLACE INTO korni007_ESP.devices(device_id, device_type) VALUES('%s', '%s');";
char SELECT1[] = "SELECT tryb FROM korni007_ESP.action LIMIT 1;";
char SELECT2[] = "SELECT status, czas,CURRENT_TIMESTAMP,TIMEDIFF(CURRENT_TIMESTAMP, czas)  FROM korni007_ESP.esp ORDER BY id DESC LIMIT 1;";
char query[512];
int count = 0;
char user[] = "korni007_esp";
char passwordSQL[] = "f0d3f252";
IPAddress server_addr(194, 181, 122, 36);
String action;
String measureTime;
String currentTime;
String status;
String diff;
void setup() {
  sprintf(query, INSERT, devices_id, devices_type);
  pinMode(pin, OUTPUT);
  digitalWrite(pin, HIGH);
  Serial.begin(115200);
  delay(10);
  Serial.print("Łączenie z "); Serial.println(ssid);
  WiFi.config(ip, gateway, subnet);
  WiFi.begin(ssid, password);
  int wifi_ctr = 0;
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("Połączono z siecią WiFi");
  Serial.println("IP: " + WiFi.localIP());
  
  while (conn.connect(server_addr, 3306, user, passwordSQL) != true) {
    delay(200);
    Serial.print(".");
  }
  
  Serial.println("");
  Serial.println("Połączono z bazą danych");
  sendMysql();
  Serial.println("Wysłanie id wykonane");

}

void loop() {
	
  if (WiFi.status() != WL_CONNECTED) {
    while (WiFi.status() != WL_CONNECTED) {
      delay(500);
      Serial.print("Wi.");
    }
    Serial.println("Połączono z WiFi");
  }
  
    /*while (conn.connect(server_addr, 3306, user, passwordSQL) != true) {
      delay(200);
      Serial.print("SQ.");
    
    }*/
  
  //pobranie info o trybie
    if (conn.connect(server_addr, 3306, user, passwordSQL)) {
      sendSelect();
    }
  
  if (action == "manual") {
    Serial.print("Łączenie z "); Serial.println(host);
    manual();
  }
  else if (action == "auto") {
    
    //zapytanie o czas i status
    
    if (conn.connect(server_addr, 3306, user, passwordSQL)) {
      sendSelect2();
    }
    String diffMinutesDec = diff.substring(3);
    if (status == "Sucho" && (diffMinutesDec == "1" || diffMinutesDec == "0")) {
      digitalWrite(pin, LOW);
      Serial.print("ustawienie stanu wysokiego");
      Serial.println("ON");

    }
    else {
      digitalWrite(pin, HIGH);
      Serial.print("ustawienie stanu wysokiego");
      Serial.println("OFF");
    }
  }


}

void sendMysql() {
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  cur_mem->execute(query);
  Serial.println("query");
  count = 1;
  delete cur_mem;
}
void sendSelect() {
  Serial.println("\nPobranie informacji o trybie\n");
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  cur_mem->execute(SELECT1, true);
  column_names *cols = cur_mem->get_columns();
  for (int f = 0; f < cols->num_fields; f++) {
    Serial.print(cols->fields[f]->name);
    if (f < cols->num_fields - 1) {
      Serial.print(", ");
    }
  }
  Serial.println();
  row_values *row = NULL;
  do {
    row = cur_mem->get_next_row();
    if (row != NULL) {
      for (int f = 0; f < cols->num_fields; f++) {
        action = row->values[f];
        Serial.print(row->values[f]);
        if (f < cols->num_fields - 1) {
          Serial.print(", ");
        }
      }
      Serial.println();
    }
  } while (row != NULL);
  delete cur_mem;

}

void manual() {
  const int httpPort = 80;
  if (!client.connect(host, httpPort)) {
    Serial.print("Błąd połączenia z "); Serial.println(host);
    return;
  }

  client.print(String("GET ") + path + " HTTP/1.1\r\n" +
    "Host: " + host + "\r\n" +
    "Connection: keep-alive\r\n\r\n");
  Serial.println("GET zapytanie wykonane");
  delay(500); // oczekiwanie na odpwoiedz serwera

        // odczytanie odpowiedzi
  String section = "header";
  while (client.available()) {
    String line = client.readStringUntil('\r');
    if (section == "header") {
      if (line == "\n") {
        section = "json";
      }
    }
    else if (section == "json") {
      section = "ignore";
      String result = line.substring(1);
      Serial.println("pobranie json wykonane");

      int size = result.length() + 1;
      char json[size];
      result.toCharArray(json, size);
      StaticJsonBuffer<200> jsonBuffer;
      JsonObject& json_parsed = jsonBuffer.parseObject(json);
      Serial.println("JSON buffer wykonane");

      if (!json_parsed.success())
      {
        Serial.println("parseObject() failed");
        return;
      }


      if (strcmp(json_parsed["on_off"], "on") == 0) { //strcmp- porównanie dwóch stringów - zwraca 0, gdy są takie same
        Serial.println("porownanie z on wykonane");
        digitalWrite(pin, LOW);
        Serial.println("ustawienie stanu wysokiego");
        Serial.println("ON");
      }
      else {
        digitalWrite(pin, HIGH);
        Serial.println("OFF");
      }
    }
  }
}

void sendSelect2() {
  Serial.println("\nPobranie statusu i czasu");
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  cur_mem->execute(SELECT2, true);
  column_names *cols = cur_mem->get_columns();
  for (int f = 0; f < cols->num_fields; f++) {
    Serial.print(cols->fields[f]->name);
    
  }
  Serial.println();
  row_values *row = NULL;
  row = cur_mem->get_next_row();
  status = row->values[0];
  measureTime =  row->values[1];
  currentTime =  row->values[2];
  diff = row->values[3];
  Serial.println(status + " " + measureTime + " " + currentTime + " " + diff);
  delete cur_mem;
}




