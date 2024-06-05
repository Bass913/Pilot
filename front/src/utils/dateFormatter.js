export function formatDate(date, justDate = true) {
	const dateObj = new Date(date);
	const today = new Date();
	if (
		dateObj.getDate() === today.getDate() &&
		dateObj.getMonth() === today.getMonth() &&
		dateObj.getFullYear() === today.getFullYear()
	) {
		if (justDate) {
			return today.toLocaleDateString();
		} else {
			return `Aujourd'hui à ${dateObj.toLocaleTimeString([], {
				hour: "2-digit",
				minute: "2-digit",
			})}`;
		}
	} else {
		if (justDate) {
			return dateObj.toLocaleDateString();
		} else {
			return dateObj.toLocaleString();
		}
	}
}
