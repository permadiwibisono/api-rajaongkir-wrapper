# api-rajaongkir-wrapper
This is wrapper to access all api of Raja Ongkir. You cannot direct access to their API, you need some wrapper.

Get your api now register at http://rajaongkir.com/akun/daftar

Before you used this code, you need to change line of this code:
- $_key (index.php)  -> your api key
- $origin (controller/costController.php) -> origin id of your origin city. Default 457 origin id of Tangerang Selatan.
- $_apiUrl (index.php) -> your api url depending on the package of API chosen. Example: Starter - "http://api.rajaongkir.com/starter"

API for Province
- URL : /api/province/{id} or /api/?controller=province&id={id}
- Method : GET
Parameter
- {id} | int | optional

API for City
- URL : /api/city/{id}?province={provId} or /api/?controller=city&id={id}&province={provId}
- Method : GET
Parameter
- {id} | int | optional
- {provId} | int | optional


API for Cost
- URL : /api/cost?destination={destination}&courier={courier}&service={service}&weight={weight} or
/api/?controller=cost&destination={destination}&courier={courier}&service={service}&weight={weight}
- Method : POST
Parameter
- {destination} : your city name (example:"Jakarta Selatan")
- {weight} : weight of your item in gram (example: 1000)
- {courier} : courier code (example: "JNE" see list of courier at http://rajaongkir.com/dokumentasi#daftar-kurir)
- {service} : service code (example: "YES")

