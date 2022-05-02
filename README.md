#Python
Portfolio crypto: Program developed in python and php, which allows monitoring crypto assets, opening, adding, decreasing and closing positions (without connection to exchanges), creating wallets, updating prices at 15-minute intervals, statistics and monitoring of a portfolio .

- It is required to fill chat_id and telegram token data in the library/config.py file
- The update_symbols.py and delete_symbols.py files must be run as a service once every 15 minutes to update portfolio data.
- The portfolio_crypto.sql file has the mysql database, which must be imported into the server, and make the settings both in library/config.py and in db_config.php

This project was carried out with:
-Python 3.8.10
-PHP 7.0
- mysql 8.0
-Bootstrap 3.3.7
