#!/usr/bin/python
#
# Copyright (c) 2008 Massachusetts Institute of Technology
# 
# Licensed under the MIT License
# Redistributions of files must retain the above copyright notice.
# 
#

import os

os.system("rm wurfl.xml web_browsers_patch.xml")
os.system("wget http://wurfl.sourceforge.net/wurfl.xml")
os.system("wget http://wurfl.sourceforge.net/web_browsers_patch.xml")

main_fp = open('wurfl.xml','r')
main_xml = main_fp.read()
main_fp.close()

patch_fp = open('web_browsers_patch.xml','r')
patch_xml = patch_fp.read()
patch_fp.close()

full_fp = open('wurfl_full.xml','w')
main_xml = main_xml.replace('</devices>','').replace('</wurfl>','')
patch_xml = patch_xml.replace('<devices>','').replace('<wurfl_patch>','').replace('</wurfl_patch>','</wurfl>')

full_fp.write(main_xml + patch_xml)
full_fp.close()

