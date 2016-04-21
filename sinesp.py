#!/usr/bin/python

import sys
import json
from sinesp_client import SinespClient

sc = SinespClient()
result = sc.search(sys.argv[1])
print json.dumps(result)
