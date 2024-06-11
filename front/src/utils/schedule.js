import weekDays from "../lib/weekDays";

function getDateFromSlotTime(date, time) {
	// date at format "YYYY-MM-DD" and time at format "HH:MM"
	const [year, month, day] = date.split("-");
	const [hours, minutes] = time.split(":");
	return new Date(year, month - 1, day, hours, minutes);
}

function getDateFromDateTimeString(dateTimeString) {
	const date = new Date(dateTimeString);
	const hours = date.getUTCHours().toString().padStart(2, "0");
	const minutes = date.getUTCMinutes().toString().padStart(2, "0");
	const seconds = date.getUTCSeconds().toString().padStart(2, "0");

	return `${hours}:${minutes}:${seconds}`;
}

function getDays(startDate, endDate) {
	const days = [];

	const start = new Date(startDate);
	const end = new Date(endDate);

	for (
		let date = new Date(start);
		date <= end;
		date.setDate(date.getDate() + 1)
	) {
		const isoDate = date.toISOString().substring(0, 10);
		days.push(isoDate);
	}

	return days;
}

function getFormattedDate(date, language) {
	const options = {
		weekday: "long",
		year: "numeric",
		month: "long",
		day: "numeric",
	};

	const lang = language === "en" ? "en-GB" : "fr-FR";

	const formatter = new Intl.DateTimeFormat(lang, options);

	return formatter.format(new Date(date));
}

function getTimeSlotsFromSchedule(days, schedules) {
	const timeSlots = {};

	days.forEach((day) => {
		const weekDay = weekDays[new Date(day).getDay()];
		const schedule = schedules.find(
			(schedule) => schedule.dayOfWeek === weekDay
		);
		const openingHour = getDateFromDateTimeString(schedule?.startTime);
		const closingHour = getDateFromDateTimeString(schedule?.endTime);

		if (!openingHour || !closingHour) {
			timeSlots[day] = [];
			return;
		}

		const [openingHourHours, openingHourMinutes] = openingHour
			.split(":")
			.map(Number);
		const [closingHourHours, closingHourMinutes] = closingHour
			.split(":")
			.map(Number);

		const openingHourTotalMinutes =
			openingHourHours * 60 + openingHourMinutes;
		const closingHourTotalMinutes =
			closingHourHours * 60 + closingHourMinutes;

		const timeSlotsOfDay = [];
		for (
			let totalMinutes = openingHourTotalMinutes;
			totalMinutes < closingHourTotalMinutes;
			totalMinutes += 30
		) {
			const hours = Math.floor(totalMinutes / 60);
			const minutes = totalMinutes % 60;
			const time = `${hours}:${minutes < 10 ? "0" + minutes : minutes}`;
			timeSlotsOfDay.push(time);
		}

		timeSlots[day] = timeSlotsOfDay;
	});

	return timeSlots;
}

function getTimeSlotsWithAvailability(
	timeSlots,
	unavailabilities = [],
	employeeSchedules = []
) {
	const timeSlotsWithAvailability = {};

	Object.keys(timeSlots).forEach((day) => {
		const dayTimeSlots = timeSlots[day];

		timeSlotsWithAvailability[day] = dayTimeSlots.map((day) => ({
			time: day,
			available: true,
		}));

		unavailabilities.forEach((unavailability) => {
			const dayDate = new Date(day);
			const unavailabilityStart = unavailability.startDate;
			const unavailabilityEnd = unavailability.endDate;
			const unavailabilityStartDate = new Date(unavailabilityStart);
			const unavailabilityEndDate = new Date(unavailabilityEnd);
			if (
				dayDate.getDate() >= unavailabilityStartDate.getDate() &&
				dayDate.getDate() <= unavailabilityEndDate.getDate()
			) {
				timeSlotsWithAvailability[day].forEach((timeSlot) => {
					const dayDate = getDateFromSlotTime(day, timeSlot.time);
					dayDate.setHours(dayDate.getHours() + 2);
					if (
						dayDate >= unavailabilityStartDate &&
						dayDate <= unavailabilityEndDate
					) {
						timeSlot.available = false;
					}
				});
			}
		});

		employeeSchedules.forEach((schedule) => {
			const dayDate = new Date(day);
			const scheduleStart = schedule.startDate;
			const scheduleEnd = schedule.endDate;
			const scheduleStartDate = new Date(scheduleStart);
			const scheduleEndDate = new Date(scheduleEnd);
			if (
				dayDate.getDate() >= scheduleStartDate.getDate() &&
				dayDate.getDate() <= scheduleEndDate.getDate()
			) {
				timeSlotsWithAvailability[day].forEach((timeSlot) => {
					const dayDate = getDateFromSlotTime(day, timeSlot.time);
					dayDate.setHours(dayDate.getHours() + 2);
					if (
						dayDate >= scheduleStartDate &&
						dayDate <= scheduleEndDate
					) {
						timeSlot.available = false;
					}
				});
			}
		});
	});

	return timeSlotsWithAvailability;
}

export {
	getTimeSlotsWithAvailability,
	getDays,
	getTimeSlotsFromSchedule,
	getFormattedDate,
};
