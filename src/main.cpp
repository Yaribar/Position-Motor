/*
    Name:       Position-Motor
    Author:     YARIBAR
*/

#include <Arduino.h>
#include "PID_Control.h"
#include "PWM_ESP32.h"
#include <WiFi.h>
#include "NiMOV.h"
#include <PubSubClient.h>
#include "Useful_Methods.h"

#define SAMPLING_PERIOD 1000
#define INTERVAL 3
#define CW 1
#define CCW 0

const uint8_t channelPinA = 25;
const uint8_t channelPinB = 26;
int8_t QEM [16] = {0,-1,1,2,1,0,2,-1,-1,2,0,1,2,1,-1,0};
uint8_t old=0, new_value=0; 
int8_t out;
const byte button = 33;

const int maxSteps = 1000;
volatile int16_t ISRCounter = 0;
int16_t counter = 0;

//*************************
//** MQTT CONFIGURATION ***
//*************************

const String serial_number = "123456789";

const char* ssid = "bluetele9C32"; //WiFi Network
const char* password = "2B6FF3E1"; //WiFi's password

const char *mqtt_server = "c-iot.ml"; //Domain name or IP
const uint16_t mqtt_port = 1883; //MQTT port TCP
const char *mqtt_user = "web_client"; //MQTT user
const char *mqtt_pass = "121212"; //MQTT password

WiFiClient espClient; //Create an object WiFi to connect to internet
PubSubClient client(espClient); //The MQTT protocol will work with the connection done through WiFi

PIDCONTROL PID(12, 11, 0, 1,8);
NiMOV HBRIDGE(AIN1,0, AIN2, 1,10000, 10,100);

float pidData = 0, error = 0, elapsedTime = 0;
float desiredAngleS = 0;
float currentPos=0;
float pidConstants[]={0.0,0.0,0.0};
boolean previous=false;

long lastMsg = 0; //Variable to save last message
char msg[25]; //Character array to receive message

//*************************
//**FUNCTION DECLARATION***
//*************************

void callback(char* topic, byte* payload, uint length);
void reconnect();
void setup_wifi();

void ISRencoder()
{
    old = new_value;
    new_value = digitalRead (channelPinA) * 2 + digitalRead (channelPinB); // Convert binary input to decimal value
    out = QEM [old * 4 + new_value];
    ISRCounter = ISRCounter + out;
    if (ISRCounter > maxSteps) ISRCounter=maxSteps;
    if (ISRCounter < -1*maxSteps) ISRCounter=-1*maxSteps;
}
 
//************************
//*******  SETUP  ********
//************************

void setup() {
    Serial.begin(115200); //initialize serial port
    randomSeed(micros());
    setup_wifi();
    pinMode(channelPinA, INPUT_PULLUP); 
    pinMode(channelPinB, INPUT_PULLUP); 
    pinMode(button, INPUT_PULLUP); 
    attachInterrupt(digitalPinToInterrupt(channelPinA), ISRencoder, CHANGE);
   attachInterrupt(digitalPinToInterrupt(channelPinB), ISRencoder, CHANGE);

    client.setServer(mqtt_server, mqtt_port); // MQTT Broker setup
    client.setCallback(callback); //Whenever a message arrives we call this fucntion
}

//*************************
//*******   LOOP   ********
//*************************

void loop() {
    if(!client.connected()){//Check if the board is connected to the server
        reconnect();
    }

    client.loop();

    error= currentPos - desiredAngleS;
    pidData = PID.Data(error);
    HBRIDGE.setSpeed(pidData);
    
    if (counter != ISRCounter){
        counter = ISRCounter;
        currentPos=mapFloat(counter,0,909,0,360);
        //Serial.print("Count: ");
        //Serial.println(counter);
    }

    if(digitalRead(button)==LOW){
        Serial.print("Button pressed");
        counter=0;
        ISRCounter=0;
    }

    long now = millis(); //this variable is useful to set a sample time
    if(now - lastMsg > SAMPLING_PERIOD){
        lastMsg = now;
        String msg_send = String(currentPos);
        msg_send.toCharArray(msg, 25);

        char topic[25];
        String topic_aux = serial_number + "/pos_value";
        topic_aux.toCharArray(topic,25);

        client.publish(topic, msg);
        Serial.println(pidData);
    }
}

//*************************
//*****WIFI CONNECTION*****
//*************************

void setup_wifi(){
    delay(10);

    //if(!WiFi.config(local_IP, gateway, subnet, primaryDNS, secondaryDNS)){
    //    Serial.println("STA Failed to configure");
    //}

    Serial.println();
    Serial.printf("Connecing to %s",ssid);

    WiFi.begin(ssid,password);

    while (WiFi.status() != WL_CONNECTED){ //If there is no connection to the network the procces won't continue
        delay(500);
        Serial.print(".");
    }
    Serial.printf("\nConnected to WiFi network!\n");
    Serial.println("IP: ");
    Serial.println(WiFi.localIP());
}

//*************************
//******  LISTENER  *******
//*************************

void callback(char* topic, byte* payload, uint length){
    String incoming = "";
    Serial.print("Message received from -> ");
    Serial.print(topic);
	Serial.println("");

    //As date is send through char array this for concatenate the information in the String "incoming"
    for (int i = 0; i < length; i++)
    {
        incoming += (char)payload[i];
    }
    incoming.trim(); //Get a version of the String with any leading and trailing whitespace removed
    Serial.println("Mensaje ->" + incoming);

    String str_topic(topic);
    if(str_topic == serial_number + "/constants"){
        parseString(incoming, ",", pidConstants);
        PID.changeConstants(pidConstants[0],pidConstants[1],pidConstants[2]);         
    }
    if(str_topic == serial_number + "/pos"){
        desiredAngleS = incoming.toFloat(); 
    }
}

//*************************
//****CONNECTION MQTT******
//*************************

void reconnect(){
    while(!client.connected()){
        Serial.print("Trying connection MQTT...");
        //Create a client ID
        String clientId = "esp32_";
        clientId += String(random(0xffff),HEX);//If there are frequent disconnections this allow the board to connect with a different ID
                                
        if(client.connect(clientId.c_str(),mqtt_user,mqtt_pass)){//connects to MQTT
            Serial.println("Connected!");

            char topic[25];
            String topic_aux = serial_number + "/constants";
            topic_aux.toCharArray(topic,25);
            client.subscribe(topic);//subscribe to topic constants

            char topic2[25];
            String topic_aux2 = serial_number + "/pos";
            topic_aux2.toCharArray(topic2,25);
            client.subscribe(topic2);//subscribe to topic  pos
            
        }
        else{ //If the conection fails try again
            Serial.print("fail :( with error -> ");
            Serial.print(client.state());
            Serial.println(" Try again in 5 seconds");

            delay(5000);
        }
    }
}