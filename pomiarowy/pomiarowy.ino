#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>
#include <MySQL_Encrypt_Sha1.h>
#include <MySQL_Packet.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <DallasTemperature.h>
#include <OneWire.h>



#define ONE_WIRE_BUS 4 //pin dla czujnika temperatury
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

char ssid[] = "INEA-137828";
char pass[] = "ALCLF4A0A9FB";
//char ssid[] = "HUAWEI-E5186-D603";
//char pass[] = "TNE9424QNR0";
char statusMoist[15];
char tempc[10];
WiFiServer server(80);
IPAddress ip(192, 168, 1, 9);
//IPAddress ip(192, 168, 8, 101);
IPAddress gateway(192, 168, 1, 1);
//IPAddress gateway(192, 168, 8, 1);
IPAddress subnet(255, 255, 255, 0);

WiFiClient client;
MySQL_Connection conn((Client *)&client);

//char UPDATE_MOIST[] = "INSERT korni007_ESP.esp SET moist = %d WHERE id =1;";
//char UPDATE_TEMP[] = "INSERT korni007_ESP.esp SET temp = %s WHERE id =1;";
//char UPDATE_STATUS[] = "UPDATE korni007_ESP.esp SET status = '%s' WHERE id =1;";

char INSERT[] = "INSERT INTO korni007_ESP.esp(moist, temp, status) VALUES(%d , %s , '%s');";
char query[256];
//char query1[128];
//char query2[128];
//char query3[128];
char query4[45] = "SET NAMES `utf8` COLLATE `utf8_polish_ci`";
char user[] = "korni007_esp";
char password[] = "f0d3f252";
//unsigned int licznik = 0;

IPAddress server_addr(194, 181, 122, 36);


void setup() {

  Serial.begin(115200);

  Serial.println("Przygotowanie połączenia");
  Serial.print(F("Ustawione ip to : "));
  Serial.println(ip);

  Serial.println("");
  Serial.println("");
  Serial.print("Łączenie z  ");
  Serial.println(ssid);
  WiFi.config(ip, gateway, subnet);
  WiFi.begin(ssid, pass);

  while (WiFi.status() != WL_CONNECTED) {
    delay(200);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("Połączono z WiFi");

  Serial.println("");
  Serial.print("Lokalne IP: ");
  Serial.print(WiFi.localIP());
  Serial.println("");

  Serial.println("Łączenie z bazą danych");

  while (conn.connect(server_addr, 3306, user, password) != true) {
    delay(200);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("Połączono z serwer SQL");

  utfMysql();


}

void loop() {
  if (WiFi.status() != WL_CONNECTED) { WiFi.begin(ssid, pass); }
  readValues();
  sendMysql();
  ESP.deepSleep(60* 1000);
  delay(100);
  //delay(1000);
  /*
  if(++licznik >= 5){
  ESP.deepSleep(20* 1000 * 1000);
  delay(100);
  licznik = 0;
  }
  */
  
  
}


void readValues() {

  unsigned int analogValue = analogRead(A0);
  float voltage = analogValue * (3.3 / 1023.0);
  unsigned int chartValue = (analogValue * 100) / 1023;
  chartValue = 100 - chartValue;

  sensors.requestTemperatures();
  float temperatura = (sensors.getTempCByIndex(0));
  int temp1 = temperatura;
  int temp2 = (temperatura - temp1) * 100;
  snprintf(tempc, 10, "%02d.%02d\n", temp1, temp2);
  if (chartValue >= 0 && chartValue < 40) sprintf(statusMoist, "Sucho");
  else if (chartValue >= 40 && chartValue < 70) sprintf(statusMoist, "Prawidłowy");
  else sprintf(statusMoist, "Mokro");
  
  sprintf(query, INSERT, chartValue, tempc, statusMoist);
  //sprintf(query1, UPDATE_MOIST, chartValue);
//  sprintf(query2, UPDATE_TEMP, tempc);
//  sprintf(query3, UPDATE_STATUS, statusMoist);
  
  Serial.print("Voltage: ");
  Serial.println(voltage);
  Serial.print("Value: ");
  Serial.println(analogValue);
  Serial.print("Wilgotność: ");
  Serial.println(chartValue);
  Serial.print("Temperatura: ");
  Serial.println(tempc);
  Serial.print("Status: ");
  Serial.println(statusMoist);
  Serial.println();

}

void sendMysql() {
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
    cur_mem->execute(query);
//  cur_mem->execute(query1);
//  delay(200);
//  cur_mem->execute(query2);
//  delay(200);
//  cur_mem->execute(query3);
  //delay(200);
  //cur_mem->execute(query4);
 
  delete cur_mem;
}
void utfMysql() {
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  
  cur_mem->execute(query4);
 
  delete cur_mem;
}




