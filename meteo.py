from datetime import datetime
import board
import busio
from  adafruit_bme280 import basic as bme280
from RPLCD.i2c import CharLCD
import mysql.connector
import requests

def connecter_bdd():
    #connexion base de données
    db = mysql.connector.connect(
        host="localhost",
        user="robertmeteo",
        password="trebor",
        database="meteobdd"
    )

    return db

def recuperer_valeurs_capteur():

    #params i2c et capteur bme280
    i2c = busio.I2C(board.SCL, board.SDA)
    sensor = bme280.Adafruit_BME280_I2C(i2c, address=0x76)

    #ajoute les valeurs du capteur dans variables

    temperature = sensor.temperature
    pressure = sensor.pressure
    humidity = sensor.humidity

    return temperature,pressure,humidity

def afficher_lcd(temperature,humidity):
    #params lcd
    lcd = CharLCD(i2c_expander='PCF8574', address=0x27, port=1, cols=16, rows=2, dotsize=8)
    lcd.clear()

    #affiche les variables sur le lcd
    lcd.write_string(f"TEMP: {temperature:.2f}C\n\r")
    lcd.write_string(f"HUMIDITY: {humidity:.2f}%")

def recuperer_valeurs_api():
    #url d'appel de l'api
    url = "http://api.openweathermap.org/data/2.5/weather?q=pau&lang=fr&units=metric&appid=477e2daf9d3e6f637dcbe5143cb58937"

    #execute une requete sur l'api et ajoute les valeurs dans des variables
    response = requests.get(url)

    if response.status_code == 200:

        weather_data = response.json()
        desc = weather_data['weather'][0]['description']
        api_temperature = weather_data['main']['temp']
        temperature_feels_like = weather_data['main']['feels_like']
        api_pressure = weather_data['main']['pressure']
        api_humidity = weather_data['main']['humidity']

        return api_temperature,api_pressure,api_humidity,temperature_feels_like,desc
    else:
        print("Erreur lors de la récupération des données de l'API")
        raise SystemExit

def ecrire_bdd(*args):
    #ajoute les relevés dans la table readings
    if len(args) == 5:        
        args[0].cursor().execute(f"INSERT INTO readings (temperature, pressure, humidity, date_time) VALUES (%s,%s,%s,%s)", (args[1], args[2], args[3], args[4]))
    elif len(args) == 7:
        args[0].cursor().execute(f"INSERT INTO apireadings (temperature, temperature_feels_like, pressure, humidity, description, date_time) VALUES (%s,%s,%s,%s,%s,%s)", (args[1],args[4], args[2], args[3], args[5], args[6]))



def main():
  date_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
  db = connecter_bdd()
  temperature,pressure,humidity = recuperer_valeurs_capteur()
  afficher_lcd(temperature,humidity)
  ecrire_bdd(db,temperature,pressure,humidity,date_time)
  temperature,pressure,humidity,temperature_feels_like,desc = recuperer_valeurs_api()
  ecrire_bdd(db,temperature,pressure,humidity,temperature_feels_like,desc,date_time)

  db.commit()
  db.cursor().close()
  db.close()
  

if __name__ == '__main__':
    main()


