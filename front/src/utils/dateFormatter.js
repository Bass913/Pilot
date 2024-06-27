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

function getISODateFromSlot(selectedSlot) {
	const { day, timeSlot } = selectedSlot;
	const [hour, minute] = timeSlot.split(":");
	const formattedHour = hour.padStart(2, "0");
	const formattedTimeSlot = `${formattedHour}:${minute}`;
	const dateTimeString = `${day}T${formattedTimeSlot}:00+00:00`;
	const date = new Date(dateTimeString);

	return date.toISOString();
}

function getISODateFromDate(date) {
	const dateObj = new Date(date);
	const year = dateObj.getFullYear();
	const month = (dateObj.getMonth() + 1).toString().padStart(2, "0");
	const day = dateObj.getDate().toString().padStart(2, "0");
	const hour = dateObj.getHours().toString().padStart(2, "0");
	const minute = dateObj.getMinutes().toString().padStart(2, "0");

	return `${year}-${month}-${day}T${hour}:${minute}:00+00:00`;
}

export { formatDate, getISODateFromSlot, getISODateFromDate };
