function formatDate(date) {
	const dateObj = new Date(date);
	const options = {
		weekday: "long",
		year: "numeric",
		month: "long",
		day: "numeric",
	};
	const datePart = dateObj.toLocaleDateString(undefined, options);
	const timePart = dateObj.toLocaleTimeString([], {
		hour: "2-digit",
		minute: "2-digit",
	});
	return `${datePart} à ${timePart}`;
}

function getFormattedDateFromISO(dateString) {
	const date = new Date(dateString);

	if (isNaN(date.getTime())) {
		return "Invalid Date";
	}

	const day = String(date.getUTCDate()).padStart(2, "0");
	const month = String(date.getUTCMonth() + 1).padStart(2, "0");
	const year = date.getUTCFullYear();

	return `${day}/${month}/${year}`;
}

function getISODateFromSlot(selectedSlot) {
	const { day, timeSlot } = selectedSlot;
	const [hour, minute] = timeSlot.split(":");
	const formattedHour = hour.padStart(2, "0");
	const formattedTimeSlot = `${formattedHour}:${minute}`;
	const dateTimeString = `${day}T${formattedTimeSlot}:00+00:00`;
	const date = new Date(dateTimeString);

	return date.toISOString();
}

export { formatDate, getISODateFromSlot, getFormattedDateFromISO };
