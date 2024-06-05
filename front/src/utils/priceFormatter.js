export const formatPrice = (price) => {
	const formattedAmount = (price / 100).toLocaleString("fr-FR", {
		style: "currency",
		currency: "EUR",
		minimumFractionDigits: 2,
		maximumFractionDigits: 2,
	});
	return formattedAmount;
};

export const getTotalWitoutVat = (items) => {
	return items.reduce((acc, item) => {
		return acc + item.unitPrice * item.quantity;
	}, 0);
};

export const getTotalVat = (items) => {
	return items.reduce((acc, item) => {
		return acc + (item.unitPrice * item.quantity * item.vat) / 100;
	}, 0);
};

export const getTotalWithVat = (items) => {
	return items.reduce((acc, item) => {
		return acc + item.total;
	}, 0);
};

export const getVATDetails = (items) => {
	const vatRates = items.map((item) => item.vat);
	const vatRatesSet = new Set(vatRates);
	const vatRatesArray = [...vatRatesSet];
	const vatDetails = vatRatesArray.map((vatRate) => {
		const base = items.reduce((acc, item) => {
			if (item.vat === vatRate) {
				return acc + item.unitPrice * item.quantity;
			}
			return acc;
		}, 0);
		const amount = items.reduce((acc, item) => {
			if (item.vat === vatRate) {
				return acc + (item.unitPrice * item.quantity * item.vat) / 100;
			}
			return acc;
		}, 0);
		return {
			rate: vatRate,
			amount,
			base,
		};
	});
	return vatDetails;
};
