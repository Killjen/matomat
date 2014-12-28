#!/usr/bin/python
import requests
import re

payload = {'type': 'check', 'id': '9999999'}

s = requests.Session()
response = s.post("http://localhost", data=payload)
print response.text