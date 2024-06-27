import { useTranslation } from "react-i18next";

function CompanySchedule({ schedules }) {
	console.log(schedules);
	const { t } = useTranslation();

	const days = {
		monday: t("monday"),
		tuesday: t("tuesday"),
		wednesday: t("wednesday"),
		thursday: t("thursday"),
		friday: t("friday"),
		saturday: t("saturday"),
		sunday: t("sunday"),
	};

	const orderedDays = Object.keys(days);

	const sortedSchedules = schedules.sort((a, b) => {
		return (
			orderedDays.indexOf(a.dayOfWeek) - orderedDays.indexOf(b.dayOfWeek)
		);
	});

	const getHourFromDate = (date) => {
		const hour = date.split("T")[1].split(":");
		return `${hour[0]}:${hour[1]}`;
	};

	return (
		<div className="bg-white px-5 py-2 rounded-md shadow-md">
			<ul className="mx-5 text-gray-800">
				{sortedSchedules.map(
					({ dayOfWeek, startTime, endTime }, index, array) => (
						<li
							key={dayOfWeek}
							className={`flex justify-between items-center py-4 ${index !== array.length - 1 && "border-b border-gray-200"}`}
						>
							<span>{days[dayOfWeek]}</span>
							<span>
								{!startTime && !endTime
									? t("closed")
									: `${getHourFromDate(startTime)} - ${getHourFromDate(endTime)}`}
							</span>
						</li>
					)
				)}
			</ul>
		</div>
	);
}

export default CompanySchedule;
