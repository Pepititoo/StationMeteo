from datetime import datetime
import board
import busio
from  adafruit_bme280 import basic as bme280
from RPLCD.i2c import CharLCD
import mysql.connector
import requests

#url d'appel de l'api
url = "http://api.openweathermap.org/data/2.5/weather?q=pau&lang=fr&units=metric&appid=477e2daf9d3e6f637dcbe5143cb58937"

#connexion base de données
db = mysql.connector.connect(
	host="localhost",
	user="robertmeteo",
	password="trebor",
	database="meteobdd"
)

#params i2c et capteur bme280
i2c = busio.I2C(board.SCL, board.SDA)
bme280 = bme280.Adafruit_BME280_I2C(i2c, address=0x76)

#params lcd
lcd = CharLCD(i2c_expander='PCF8574', address=0x27, port=1, cols=16, rows=2, dotsize=8)
lcd.clear()

date_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

#ajoute les valeurs du capteur dans variables
temperature = bme280.temperature
pressure = bme280.pressure
humidity = bme280.humidity

#affiche les variables sur le lcd
lcd.write_string(f"TEMP: {temperature:.2f}C\n\r")
lcd.write_string(f"HUMIDITY: {humidity:.2f}%")

#ajoute les relevés dans la table readings
db.cursor().execute(f"INSERT INTO readings (temperature, pressure, humidity, date_time) VALUES (%s,%s,%s,%s)", (temperature, pressure, humidity, date_time));

#execute une requete sur l'api et ajoute les valeurs dans des variables
weather_data = requests.get(url).json()

desc = weather_data['weather'][0]['description']
api_temperature = weather_data['main']['temp']
temperature_feels_like = weather_data['main']['feels_like']
api_pressure = weather_data['main']['pressure']
api_humidity = weather_data['main']['humidity']

db.cursor().execute(f"INSERT INTO apireadings (temperature, temperature_feels_like, pressure, humidity, description, date_time) VALUES (%s,%s,%s,%s,%s,%s)", (api_temperature,temperature_feels_like, api_pressure, api_humidity, desc, date_time))

db.commit()
db.cursor().close()
db.close()




