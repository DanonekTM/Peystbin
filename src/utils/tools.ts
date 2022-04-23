export function timestampToDateTimeConverter(unixTime: number, withTime: boolean): string {
	const humanFormat = new Date(unixTime * 1000);

	if (withTime) {
		return (('0' + humanFormat.getUTCDate()).slice(-2) + 
		'/' + ('0' + (humanFormat.getUTCMonth() + 1)).slice(-2) +
		'/' + humanFormat.getUTCFullYear() +
		' ' + ('0' + humanFormat.getUTCHours()).slice(-2) +
		':' + ('0' + humanFormat.getUTCMinutes()).slice(-2) +
		':' + ('0' + humanFormat.getUTCSeconds()).slice(-2));
	}

	return (('0' + humanFormat.getUTCDate()).slice(-2) + 
	'/' + ('0' + (humanFormat.getUTCMonth() + 1)).slice(-2) +
	'/' + humanFormat.getUTCFullYear());
}

export function exposureConverter(exposure: string) : string {
	exposure = exposure.toLowerCase();
	exposure = exposure[0].toUpperCase() + exposure.slice(1);
	return exposure;
}

export function getDecodedJWT(token: string) : JSON {
	try {
		return JSON.parse(atob(token.split('.')[1]));
	} catch {
		return undefined;
	}
}