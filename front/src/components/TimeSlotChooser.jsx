import { getFormattedDate } from "../utils/schedule";
import { useUser } from "../hooks/useUser";

function TimeSlotChooser({
	timeSlotsWithAvailability,
	selectedSlot,
	onSlotSelection,
}) {
	const { language } = useUser();
	const handleSlotSelection = (day, timeSlot) => {
		onSlotSelection(day, timeSlot);
	};

	return (
		<div
			className={`mt-2 bg-white p-6 rounded-lg shadow-md lg:p-8 grid grid-cols-7 gap-3`}
		>
			{Object.keys(timeSlotsWithAvailability).map((day, dayIndex) => (
				<div
					key={dayIndex}
					className="flex items-center flex-col gap-1"
				>
					<p className="text-gray-800 font-normal text-sm">
						{getFormattedDate(day, language).split(" ")[0]}
					</p>
					<p className="text-gray-600 font-normal text-sm mb-2">
						{getFormattedDate(day, language)
							.split(" ")
							.slice(1)
							.join(" ")}
					</p>
					<div className="flex flex-col gap-2 w-full">
						{timeSlotsWithAvailability[day].map(
							(timeSlot, slotIndex) => (
								<label
									key={`${dayIndex}-${slotIndex}`}
									className={`text-gray-800 font-light rounded-md px-2 py-1 w-full cursor-pointer text-center font-normal h-7 flex items-center justify-center text-sm
															${
																timeSlot.available
																	? selectedSlot?.day ===
																			day &&
																		selectedSlot?.timeSlot ===
																			timeSlot.time
																		? "bg-primary-900 text-white hover:bg-primary-800"
																		: "bg-gray-100 text-gray-800 hover:bg-gray-200"
																	: "bg-white text-gray-500 cursor-not-allowed text-gray-300 line-through text-sm"
															}`}
								>
									<input
										type="radio"
										className="hidden"
										name="timeSlot"
										value={timeSlot.time}
										disabled={!timeSlot.available}
										checked={
											selectedSlot?.day === day &&
											selectedSlot?.timeSlot ===
												timeSlot.time
										}
										onChange={() =>
											handleSlotSelection(
												day,
												timeSlot.time
											)
										}
									/>
									<span>{timeSlot.time}</span>
								</label>
							)
						)}
					</div>
				</div>
			))}
		</div>
	);
}

export default TimeSlotChooser;
