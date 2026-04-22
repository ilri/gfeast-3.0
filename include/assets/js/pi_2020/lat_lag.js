const getLatLong = (countryCode) => {
	
	let lat = null;
	let lon = null;
	
	switch(true){
		case countryCode == 'AD':
            lat = 42.5000;
            lon = 1.5000;
            break;
        case countryCode == 'AE':
            lat = 24.0000;
            lon = 54.0000;
            break;
        case countryCode == 'AF':
            lat = 33.0000;
            lon = 65.0000;
            break;
        case countryCode == 'AG':
            lat = 17.0500;
            lon = -61.8000;
            break;
        case countryCode == 'AI':
            lat = 18.2500;
            lon = -63.1667;
            break;
        case countryCode == 'AL':
            lat = 41.0000;
            lon = 20.0000;
            break;
        case countryCode == 'AM':
            lat = 40.0000;
            lon = 45.0000;
            break;
        case countryCode == 'AN':
            lat = 12.2500;
            lon = -68.7500;
            break;
        case countryCode == 'AP':
            lat = 35.0000;
            lon = 105.0000;
            break;
        case countryCode == 'AO':
            lat = -12.5000;
            lon = 18.5000;
            break;
        case countryCode == 'AQ':
            lat = -90.0000;
            lon = 0.000;
            break;
        case countryCode == 'AR':
            lat = -34.0000;
            lon = -64.0000;
            break;
        case countryCode == 'AS':
            lat = -14.3333;
            lon = -170.0000;
            break;
        case countryCode == 'AT':
            lat = 47.3333;
            lon = 13.3333;
            break;
        case countryCode == 'AU':
            lat = -27.0000;
            lon = 133.0000;
            break;
        case countryCode == 'AW':
            lat = 12.5000;
            lon = -69.9667;
            break;
        case countryCode == 'AZ':
            lat = 40.5000;
            lon = 47.5000;
            break;
        case countryCode == 'BA':
            lat = 44.0000;
            lon = 18.0000;
            break;
        case countryCode == 'BB':
            lat = 13.1667;
            lon = -59.5333;
            break;
        case countryCode == 'BD':
            lat = 24.0000;
            lon = 90.0000;
            break;
        case countryCode == 'BE':
            lat = 50.8333;
            lon = 4.0000;
            break;
        case countryCode == 'BF':
            lat = 13.0000;
            lon = -2.0000;
            break;
        case countryCode == 'BG':
            lat = 43.0000;
            lon = 25.0000;
            break;
        case countryCode == 'BH':
            lat = 26.0000;
            lon = 50.5500;
            break;
        case countryCode == 'BI':
            lat = -3.5000;
            lon = 30.0000;
            break;
        case countryCode == 'BJ':
            lat = 9.5000;
            lon = 2.2500;
            break;
        case countryCode == 'BM':
            lat = 32.3333;
            lon = -64.7500;
            break;
        case countryCode == 'BN':
            lat = 4.5000;
            lon = 114.6667;
            break;
        case countryCode == 'BO':
            lat = -17.0000;
            lon = -65.0000;
            break;
        case countryCode == 'BR':
            lat = -10.0000;
            lon = -55.0000;
            break;
        case countryCode == 'BS':
            lat = 24.2500;
            lon = -76.0000;
            break;
        case countryCode == 'BT':
            lat = 27.5000;
            lon = 90.5000;
            break;
        case countryCode == 'BV':
            lat = -54.4333;
            lon = 3.4000;
            break;
        case countryCode == 'BW':
            lat = -22.0000;
            lon = 24.0000;
            break;
        case countryCode == 'BY':
            lat = 53.0000;
            lon = 28.0000;
            break;
        case countryCode == 'BZ':
            lat = 17.2500;
            lon = -88.7500;
            break;
        case countryCode == 'CA':
            lat = 60.0000;
            lon = -95.0000;
            break;
        case countryCode == 'CC':
            lat = -12.5000;
            lon = 96.8333;
            break;
        case countryCode == 'CD':
            lat = 0.0000;
            lon = 25.0000;
            break;
        case countryCode == 'CF':
            lat = 7.0000;
            lon = 21.0000;
            break;
        case countryCode == 'CG':
            lat = -1.0000;
            lon = 15.0000;
            break;
        case countryCode == 'CH':
            lat = 47.0000;
            lon = 8.0000;
            break;
        case countryCode == 'CI':
            lat = 8.0000;
            lon = -5.0000;
            break;
        case countryCode == 'CK':
            lat = -21.2333;
            lon = -159.7667;
            break;
        case countryCode == 'CL':
            lat = -30.0000;
            lon = -71.0000;
            break;
        case countryCode == 'CM':
            lat = 6.0000;
            lon = 12.0000;
            break;
        case countryCode == 'CN':
            lat = 35.0000;
            lon = 105.0000;
            break;
        case countryCode == 'CO':
            lat = 4.0000;
            lon = -72.0000;
            break;
        case countryCode == 'CR':
            lat = 10.0000;
            lon = -84.0000;
            break;
        case countryCode == 'CU':
            lat = 21.5000;
            lon = -80.0000;
            break;
        case countryCode == 'CV':
            lat = 16.0000;
            lon = -24.0000;
            break;
        case countryCode == 'CX':
            lat = -10.5000;
            lon = 105.6667;
            break;
        case countryCode == 'CY':
            lat = 35.0000;
            lon = 33.0000;
            break;
        case countryCode == 'CZ':
            lat = 49.7500;
            lon = 15.5000;
            break;
        case countryCode == 'DE':
            lat = 51.0000;
            lon = 9.0000;
            break;
        case countryCode == 'DJ':
            lat = 11.5000;
            lon = 43.0000;
            break;
        case countryCode == 'DK':
            lat = 56.0000;
            lon = 10.0000;
            break;
        case countryCode == 'DK':
            lat = 56.0000;
            lon = 10.0000;
            break;
        case countryCode == 'DM':
            lat = 15.4167;
            lon = -61.3333;
            break;
        case countryCode == 'DO':
            lat = 19.0000;
            lon = -70.6667;
            break;
        case countryCode == 'DZ':
            lat = 28.0000;
            lon = 3.0000;
            break;
        case countryCode == 'EC':
            lat = -2.0000;
            lon = -77.5000;
            break;
        case countryCode == 'EE':
            lat = 59.0000;
            lon = 26.0000;
            break;
        case countryCode == 'EG':
            lat = 27.0000;
            lon = 30.0000;
            break;
        case countryCode == 'EH':
            lat = 24.5000;
            lon = -13.0000;
            break;
        case countryCode == 'ER':
            lat = 15.0000;
            lon = 39.0000;
            break;
        case countryCode == 'ES':
            lat = 40.0000;
            lon = -4.0000;
            break;
        case countryCode == 'ET':
            lat = 8.0000;
            lon = 38.0000;
            break;
        case countryCode == 'EU':
            lat = 47.0000;
            lon = 8.0000;
            break;
        case countryCode == 'FI':
            lat = 64.0000;
            lon = 26.0000;
            break;
        case countryCode == 'FJ':
            lat = -18.0000;
            lon = 175.0000;
            break;
        case countryCode == 'FK':
            lat = -51.7500;
            lon = -59.0000;
            break;
        case countryCode == 'FM':
            lat = 6.91670;
            lon = 158.2500;
            break;
        case countryCode == 'FO':
            lat = 62.0000;
            lon = -7.0000;
            break;
        case countryCode == 'FR':
            lat = 46.0000;
            lon = 2.0000;
            break;
        case countryCode == 'GA':
            lat = -1.0000;
            lon = 11.7500;
            break;
        case countryCode == 'GB':
            lat = 54.0000;
            lon = -2.0000;
            break;
        case countryCode == 'GD':
            lat = 12.1167;
            lon = -61.6667;
            break;
        case countryCode == 'GE':
            lat = 42.0000;
            lon = 43.5000;
            break;
        case countryCode == 'GF':
            lat = 4.0000;
            lon = -53.0000;
            break;
        case countryCode == 'GH':
            lat = 8.0000;
            lon = -2.0000;
            break;
        case countryCode == 'GI':
            lat = 36.1833;
            lon = -5.3667;
            break;
        case countryCode == 'GL':
            lat = 72.0000;
            lon = -40.0000;
            break;
        case countryCode == 'GM':
            lat = 13.4667;
            lon = -16.5667;
            break;
        case countryCode == 'GN':
            lat = 11.0000;
            lon = -10.0000;
            break;
        case countryCode == 'GP':
            lat = 16.2500;
            lon = -61.5833;
            break;
        case countryCode == 'GQ':
            lat = 2.0000;
            lon = 10.0000;
            break;
        case countryCode == 'GR':
            lat = 39.0000;
            lon = 22.0000;
            break;
        case countryCode == 'GS':
            lat = -54.5000;
            lon = -37.0000;
            break;
        case countryCode == 'GT':
            lat = 15.5000;
            lon = -90.2500;
            break;
        case countryCode == 'GU':
            lat = 13.4667;
            lon = 144.7833;
            break;
        case countryCode == 'GW':
            lat = 12.0000;
            lon = -15.0000;
            break;
        case countryCode == 'GY':
            lat = 5.0000;
            lon = -59.0000;
            break;
        case countryCode == 'HK':
            lat = 22.2500;
            lon = 114.1667;
            break;
        case countryCode == 'HM':
            lat = -53.1000;
            lon = 72.5167;
            break;
        case countryCode == 'HN':
            lat = 15.0000;
            lon = -86.5000;
            break;
        case countryCode == 'HR':
            lat = 45.1667;
            lon = 15.5000;
            break;
        case countryCode == 'HT':
            lat = 19.0000;
            lon = -72.4167;
            break;
        case countryCode == 'HU':
            lat = 47.0000;
            lon = 20.0000;
            break;
        case countryCode == 'ID':
            lat = -5.0000;
            lon = 120.0000;
            break;
        case countryCode == 'IE':
            lat = 53.0000;
            lon = -8.0000;
            break;
        case countryCode == 'IL':
            lat = 31.5000;
            lon = 34.7500;
            break;
        case countryCode == 'IN':
            lat = 20.0000;
            lon = 77.0000;
            break;
        case countryCode == 'IO':
            lat = -6.0000;
            lon = 71.5000;
            break;
        case countryCode == 'IQ':
            lat = 33.0000;
            lon = 44.0000;
            break;
        case countryCode == 'IR':
            lat = 32.0000;
            lon = 53.0000;
            break;
        case countryCode == 'IS':
            lat = 65.0000;
            lon = -18.0000;
            break;
        case countryCode == 'IT':
            lat = 42.8333;
            lon = 12.8333;
            break;
        case countryCode == 'JM':
            lat = 18.2500;
            lon = -77.5000;
            break;
        case countryCode == 'JO':
            lat = 31.0000;
            lon = 36.0000;
            break;
        case countryCode == 'JP':
            lat = 36.0000;
            lon = 138.0000;
            break;
        case countryCode == 'KE':
            lat = 1.0000;
            lon = 38.0000;
            break;
        case countryCode == 'KG':
            lat = 41.0000;
            lon = 75.0000;
            break;
        case countryCode == 'KH':
            lat = 13.0000;
            lon = 105.0000;
            break;
        case countryCode == 'KI':
            lat = 1.4167;
            lon = 173.0000;
            break;
        case countryCode == 'KM':
            lat = -12.1667;
            lon = 44.2500;
            break;
        case countryCode == 'KN':
            lat = 17.3333;
            lon = -62.7500;
            break;
        case countryCode == 'KP':
            lat = 40.0000;
            lon = 127.0000;
            break;
        case countryCode == 'KR':
            lat = 37.0000;
            lon = 127.5000;
            break;
        case countryCode == 'KW':
            lat = 29.3375;
            lon = 47.6581;
            break;
        case countryCode == 'KY':
            lat = 19.5000;
            lon = -80.5000;
            break;
        case countryCode == 'KZ':
            lat = 48.0000;
            lon = 68.0000;
            break;
        case countryCode == 'LA':
            lat = 18.0000;
            lon = 105.0000;
            break;
        case countryCode == 'LB':
            lat = 33.8333;
            lon = 35.8333;
            break;
        case countryCode == 'LC':
            lat = 13.8833;
            lon = -61.1333;
            break;
        case countryCode == 'LI':
            lat = 47.1667;
            lon = 9.5333;
            break;
        case countryCode == 'LK':
            lat = 7.0000;
            lon = 81.0000;
            break;
        case countryCode == 'LR':
            lat = 6.5000;
            lon = -9.5000;
            break;
        case countryCode == 'LS':
            lat = -29.5000;
            lon = 28.5000;
            break;
        case countryCode == 'LT':
            lat = 56.0000;
            lon = 24.0000;
            break;
        case countryCode == 'LU':
            lat = 49.7500;
            lon = 6.1667;
            break;
        case countryCode == 'LV':
            lat = 57.0000;
            lon = 25.0000;
            break;
        case countryCode == 'LY':
            lat = 25.0000;
            lon = 17.0000;
            break;
        case countryCode == 'MA':
            lat = 32.00000;
            lon = -5.0000;
            break;
        case countryCode == 'MC':
            lat = 43.7333;
            lon = 7.4000;
            break;
        case countryCode == 'MD':
            lat = 47.0000;
            lon = 29.0000;
            break;
        case countryCode == 'ME':
            lat = 42.0000;
            lon = 19.0000;
            break;
        case countryCode == 'MG':
            lat = -20.0000;
            lon = 47.0000;
            break;
        case countryCode == 'MH':
            lat = 9.0000;
            lon = 168.0000;
            break;
        case countryCode == 'MK':
            lat = 41.8333;
            lon = 22.0000;
            break;
        case countryCode == 'ML':
            lat = 17.0000;
            lon = -4.0000;
            break;
        case countryCode == 'MM':
            lat = 22.0000;
            lon = 98.0000;
            break;
        case countryCode == 'MN':
            lat = 46.0000;
            lon = 105.0000;
            break;
        case countryCode == 'MO':
            lat = 22.1667;
            lon = 113.5500;
            break;
        case countryCode == 'MP':
            lat = 15.2000;
            lon = 145.7500;
            break;
        case countryCode == 'MQ':
            lat = 14.6667;
            lon = -61.0000;
            break;
        case countryCode == 'MR':
            lat = 20.0000;
            lon = -12.0000;
            break;
        case countryCode == 'MS':
            lat = 16.7500;
            lon = -62.2000;
            break;
        case countryCode == 'MT':
            lat = 35.8333;
            lon = 14.5833;
            break;
        case countryCode == 'MU':
            lat = -20.2833;
            lon = 57.5500;
            break;
        case countryCode == 'MV':
            lat = 3.2500;
            lon = 73.0000;
            break;
        case countryCode == 'MW':
            lat = -13.5000;
            lon = 34.0000;
            break;
        case countryCode == 'MX':
            lat = 23.0000;
            lon = -102.0000;
            break;
        case countryCode == 'MY':
            lat = 2.5000;
            lon = 112.5000;
            break;
        case countryCode == 'MZ':
            lat = -18.2500;
            lon = 35.0000;
            break;
        case countryCode == 'NA':
            lat = -22.0000;
            lon = 17.0000;
            break;
        case countryCode == 'NC':
            lat = -21.5000;
            lon = 165.5000;
            break;
        case countryCode == 'NE':
            lat = 16.0000;
            lon = 8.0000;
            break;
        case countryCode == 'NF':
            lat = -29.0333;
            lon = 167.9500;
            break;
        case countryCode == 'NG':
            lat = 10.0000;
            lon = 8.0000;
            break;
        case countryCode == 'NI':
            lat = 13.0000;
            lon = -85.0000;
            break;
        case countryCode == 'NL':
            lat = 52.5000;
            lon = 5.7500;
            break;
        case countryCode == 'NO':
            lat = 62.0000;
            lon = 10.0000;
            break;
        case countryCode == 'NP':
            lat = 28.0000;
            lon = 84.0000;
            break;
        case countryCode == 'NR':
            lat = -0.5333;
            lon = 166.9167;
            break;
        case countryCode == 'NU':
            lat = -19.0333;
            lon = -169.8667;
            break;
        case countryCode == 'NZ':
            lat = -41.0000;
            lon = 174.0000;
            break;
        case countryCode == 'OM':
            lat = 21.0000;
            lon = 57.0000;
            break;
        case countryCode == 'PA':
            lat = 9.0000;
            lon = -80.0000;
            break;
        case countryCode == 'PE':
            lat = -10.0000;
            lon = -76.0000;
            break;
        case countryCode == 'PF':
            lat = -15.0000;
            lon = -140.0000;
            break;
        case countryCode == 'PQ':
            lat = -15.0000;
            lon = -140.0000;
            break;
        case countryCode == 'PH':
            lat = 13.0000;
            lon = 122.0000;
            break;
        case countryCode == 'PK':
            lat = 30.0000;
            lon = 70.0000;
            break;
        case countryCode == 'PL':
            lat = 52.0000;
            lon = 20.0000;
            break;
        case countryCode == 'PM':
            lat = 46.8333;
            lon = -56.3333;
            break;
        case countryCode == 'PR':
            lat = 18.2500;
            lon = -66.5000;
            break;
        case countryCode == 'PS':
            lat = 32.0000;
            lon = 35.2500;
            break;
        case countryCode == 'PS':
            lat = 32.0000;
            lon = 35.2500;
            break;
        case countryCode == 'PT':
            lat = 39.5000;
            lon = -8.0000;
            break;
        case countryCode == 'PW':
            lat = 7.5000;
            lon = 134.5000;
            break;
        case countryCode == 'PY':
            lat = -23.0000;
            lon = -58.0000;
            break;
        case countryCode == 'QA':
            lat = 25.5000;
            lon = 51.2500;
            break;
        case countryCode == 'RE':
            lat = -21.1000;
            lon = 55.6000;
            break;
        case countryCode == 'RO':
            lat = 46.0000;
            lon = 25.0000;
            break;
        case countryCode == 'RS':
            lat = 44.0000;
            lon = 21.0000;
            break;
        case countryCode == 'RU':
            lat = 60.0000;
            lon = 100.0000;
            break;
        case countryCode == 'RW':
            lat = -2.0000;
            lon = 30.0000;
            break;
        case countryCode == 'SA':
            lat = 25.0000;
            lon = 45.0000;
            break;
        case countryCode == 'SB':
            lat = -8.0000;
            lon = 159.0000;
            break;
        case countryCode == 'SC':
            lat = -4.5833;
            lon = 55.6667;
            break;
        case countryCode == 'SD':
            lat = 15.0000;
            lon = 30.0000;
            break;
        case countryCode == 'SE':
            lat = 62.0000;
            lon = 15.0000;
            break;
        case countryCode == 'SG':
            lat = 1.3667;
            lon = 103.8000;
            break;
        case countryCode == 'SH':
            lat = -15.9333;
            lon = -5.7000;
            break;
        case countryCode == 'SI':
            lat = 46.0000;
            lon = 15.0000;
            break;
        case countryCode == 'SJ':
            lat = 78.0000;
            lon = 20.0000;
            break;
        case countryCode == 'SK':
            lat = 48.6667;
            lon = 19.5000;
            break;
        case countryCode == 'SL':
            lat = 8.5000;
            lon = -11.5000;
            break;
        case countryCode == 'SM':
            lat = 43.7667;
            lon = 12.4167;
            break;
        case countryCode == 'SN':
            lat = 14.0000;
            lon = -14.0000;
            break;
        case countryCode == 'SO':
            lat = 10.0000;
            lon = 49.0000;
            break;
        case countryCode == 'SR':
            lat = 4.0000;
            lon = -56.0000;
            break;
        case countryCode == 'ST':
            lat = 1.0000;
            lon = 7.0000;
            break;
        case countryCode == 'SV':
            lat = 13.8333;
            lon = -88.9167;
            break;
        case countryCode == 'SY':
            lat = 35.0000;
            lon = 38.0000;
            break;
        case countryCode == 'SZ':
            lat = -26.5000;
            lon = 31.5000;
            break;
        case countryCode == 'TC':
            lat = 21.7500;
            lon = -71.5833;
            break;
        case countryCode == 'TD':
            lat = 15.0000;
            lon = 19.0000;
            break;
        case countryCode == 'TF':
            lat = -43.0000;
            lon = 67.0000;
            break;
        case countryCode == 'TG':
            lat = 8.0000;
            lon = 1.1667;
            break;
        case countryCode == 'TH':
            lat = 15.0000;
            lon = 100.0000;
            break;
        case countryCode == 'TJ':
            lat = 39.0000;
            lon = 71.0000;
            break;
        case countryCode == 'TK':
            lat = 39.0000;
            lon = 71.0000;
            break;
        case countryCode == 'TM':
            lat = 40.0000;
            lon = 60.0000;
            break;
        case countryCode == 'TN':
            lat = 34.0000;
            lon = 9.0000;
            break;
        case countryCode == 'TO':
            lat = -20.0000;
            lon = -175.0000;
            break;
        case countryCode == 'TR':
            lat = 39.0000;
            lon = 35.0000;
            break;
        case countryCode == 'TT':
            lat = 11.0000;
            lon = -61.0000;
            break;
        case countryCode == 'TV':
            lat = -8.0000;
            lon = 178.0000;
            break;
        case countryCode == 'TW':
            lat = 23.5000;
            lon = 121.0000;
            break;
        case countryCode == 'TZ':
            lat = -6.0000;
            lon = 35.0000;
            break;
        case countryCode == 'UA':
            lat = 49.0000;
            lon = 32.0000;
            break;
        case countryCode == 'UG':
            lat = 1.0000;
            lon = 32.0000;
            break;
        case countryCode == 'UM':
            lat = 19.2833;
            lon = 166.6000;
            break;
        case countryCode == 'US':
            lat = 38.0000;
            lon = -97.0000;
            break;
        case countryCode == 'UY':
            lat = -33.0000;
            lon = -56.0000;
            break;
        case countryCode == 'UZ':
            lat = 41.0000;
            lon = 64.0000;
            break;
        case countryCode == 'VA':
            lat = 41.9000;
            lon = 12.4500;
            break;
        case countryCode == 'VC':
            lat = 13.2500;
            lon = -61.2000;
            break;
        case countryCode == 'VE':
            lat = 8.0000;
            lon = -66.0000;
            break;
        case countryCode == 'VG':
            lat = 18.5000;
            lon = -64.5000;
            break;
        case countryCode == 'VI':
            lat = 18.3333;
            lon = -64.5000;
            break;
        case countryCode == 'VN':
            lat = 16.0000;
            lon = 106.0000;
            break;
        case countryCode == 'VU':
            lat = -16.0000;
            lon = 167.0000;
            break;
        case countryCode == 'WF':
            lat = -13.3000;
            lon = -176.2000;
            break;
        case countryCode == 'WS':
            lat = -13.5833;
            lon = -176.2000;
            break;
        case countryCode == 'YE':
            lat = 15.0000;
            lon = 48.0000;
            break;
        case countryCode == 'YT':
            lat = -12.8333;
            lon = 45.1667;
            break;
        case countryCode == 'ZA':
            lat = -29.0000;
            lon = 24.0000;
            break;
        case countryCode == 'ZM':
            lat = -15.0000;
            lon = 30.0000;
            break;
        case countryCode == 'ZW':
            lat = -20.0000;
            lon = 30.0000;
            break;
	}
	
	return {"latitude": lat, "longitude": lon}
}