
#Flow data for stacked bars and calendar port activity visualization 

from silk import *
#import cgi, cgitb
import json
from datetime import timedelta, date,datetime
import copy 

#date range
startDate = datetime(2018, 1, 1, 0)
endDate = datetime(2019, 10, 4, 23) 

#group ports dics
groupDic = {}
portDic = {'100':0, '200':0, '300':0, '400':0, '500':0, '600':0, '700':0, '800':0, '900':0, '1023':0}
#total ports sum dic
sumDic = {}

###########################################################################

def getDic(start, end, date,hour):

	#copy portDic for each hour of a day
	groupDic[date] = {hour : copy.deepcopy(portDic)}
	#set hour value
	groupDic[date][hour]['hour'] = hour

	#Inner dictionaries for each date's total sum 
	sumDic = {'date' : date, 'value': 0}


	#traverse flow files
	for filename in FGlob(classname="all", type="all", start_date=start, end_date=end, site_config_file="/etc/silk/conf-v9/silk.conf", data_rootdir="/home/scratch/flow/rwflowpack/"):
		try:
			for rec in silkfile_open(filename, READ):#reading the flow file
				dip = str(rec.dip)
				dport = rec.dport

				#check if desired ips and within port range
				#if splitIP(ip, pos) == splitIP(dip, pos) and 0 <= dport < 1024:
				if 0 <= dport < 1024:

					#counts all individual port connections
					
					sumDic['value'] += 1
					

					#sum port activiy by groups of 100s 
					if dport < 100:
						groupDic[date][hour]['100'] += 1
						

					elif dport < 200:
						groupDic[date][hour]['200'] += 1
						

					elif dport < 300:
						groupDic[date][hour]['300'] += 1
						

					elif dport < 400:
						groupDic[date][hour]['400'] += 1
						

					elif dport < 500:
						groupDic[date][hour]['500'] += 1
						

					elif dport < 600:
						groupDic[date][hour]['600'] += 1
						

					elif dport < 700:
						groupDic[date][hour]['700'] += 1
						

					elif dport < 800:
						groupDic[date][hour]['800'] += 1
						

					elif dport < 900:
						groupDic[date][hour]['900'] += 1

					else:
						groupDic[date][hour]['1023'] += 1
		except:
			continue

	return groupDic, sumDic



################################################################################


#update begin and end hours, calls getDic to get dictionaries and creates list of dics
def getJSON():

	MASTERDIC = []

	MASTERDIC1 = []


	date = startDate

	for n in range(int ((endDate - startDate).days)):

		mDIC = {}

		mDIC1 = {}

		#hour ctr var
		hr = -1
		#move a day up
		date = startDate + timedelta(n)
		eDate = copy.deepcopy(date)

		date = date.strftime("%Y/%m/%d")


		#iterates the 24hrs
		for i in range(0,24):

			eDate = eDate + timedelta(hours=1)

			streDate = eDate.strftime("%Y/%m/%dT%H")

		
			#aumenta hora
			hr += 1
	
			#check si menor a 10 to add 0 to hour format
			if hr > 10:
				startHour = str(hr)
			

			else:
				startHour = "0" + str(hr)
			
			#update date and time
			start = date + 'T' + startHour


			pRanges, pSum = getDic(start, streDate, date, i)


			# Check if value already in mDIC
			if date in mDIC:
				mDIC[date].update({i:pRanges.values()[0][i]})
			else:
				mDIC[date] = {i:pRanges.values()[0][i]}

			#add up each hour for whole day total sum
			mDIC1 = {'date' : date, 'value': 0}
			mDIC1['value'] += pSum['value']

		#Append all hours for THAT DAY 
		MASTERDIC.append(copy.deepcopy(mDIC))


		with open("grouped-ports.json", "w") as json_file:
			json.dump(MASTERDIC, json_file)

		#add entire day's sum
		MASTERDIC1.append(copy.deepcopy(mDIC1))	


		with open("total-sum.json", "w") as json_file:
			json.dump(MASTERDIC1, json_file)


		pRanges.clear()
		pSum.clear()



####################################################################################

def main():

	getJSON()
	


####################################################################################

main()
