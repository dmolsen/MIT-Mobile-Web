#
# Copyright (c) 2008 Massachusetts Institute of Technology
# 
# Licensed under the MIT License
# Redistributions of files must retain the above copyright notice.
# 
#

from wurfl import devices
from pywurfl.algorithms import Algorithm, Tokenizer
import re

is_smart_phone = re.compile(".*?(Palm|SymbianOS|Windows CE|BlackBerry)")
is_iPhone = re.compile(".*?(iPhone|iPod|Aspen Simulator)")

def device_type(user_agent):
    if is_iPhone.match(user_agent):
        return 'iphone'

    if is_smart_phone.match(user_agent):
        return 'smart_phone'

    wurfl_obj = devices.select_ua(user_agent, search=GenericBrowserPreferred()) 
    if is_device(wurfl_obj, 'netfront_ver3'):
        # really stupid special case, for some reason wurfl
        # classifies these devices as being browsers
        return 'feature_phone'

    if is_device(wurfl_obj, 'generic_web_crawler'):
        return 'spider'
    elif is_device(wurfl_obj, 'generic_web_browser'):
        return 'computer'
    else:            
        return 'feature_phone'
        
def is_device(wurfl_obj, devs):
    if type(devs) is str:
        devs = [ devs ]
    for devid in devs:
        if wurfl_obj.devid == devid:
            return True
    if wurfl_obj.fall_back is 'root':
        return False
    else:
        fall_back = devices.select_id(wurfl_obj.fall_back)
        return is_device(fall_back, devs)

import sys
sys.path.append('apache/htdocs/mobile/lstein/build/lib.linux-x86_64-2.4/')
import Levenshtein
class GenericBrowserPreferred(Algorithm):
    """
    This is method for searching through all devices
    This method favors matching devices to a web browser
    over a mobile device
    """
 
    browser_pref = 1.05

    def __call__(self, ua, devs):
        match = min((self.match_distance(ua, x), x) for x in devs)
        print devices.devids[match[1]].devua
        return devices.devids[match[1]]

    def match_distance(self, ua, device):
        device = devices.select_id(device)
        distance = Levenshtein.distance(device.devua, ua)
        if is_device(device, "generic_web_browser"):
            distance = distance/self.browser_pref
        return distance
