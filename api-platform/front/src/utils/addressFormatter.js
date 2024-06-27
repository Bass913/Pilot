export function getAddress(provider) {
	return `${provider.address} ${provider.zipcode} ${provider.city}`;
}
