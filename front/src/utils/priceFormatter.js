export const formatPrice = (price) => {
	console.log("price", price);
	const formattedAmount = price.toLocaleString("fr-FR", {
		style: "currency",
		currency: "EUR",
		minimumFractionDigits: 2,
		maximumFractionDigits: 2,
	});
	return formattedAmount.replace(".", ",") + " €";
};