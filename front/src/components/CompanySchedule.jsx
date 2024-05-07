function CompanySchedule({ schedule }) {
	const days = {
		monday: "Lundi",
		tuesday: "Mardi",
		wednesday: "Mercredi",
		thursday: "Jeudi",
		friday: "Vendredi",
		saturday: "Samedi",
		sunday: "Dimanche",
	};

	return (
		<div className="bg-white px-5 py-2 rounded-md shadow-md">
			<ul className="mx-5 text-gray-800">
				{Object.entries(schedule).map(([day, hours], index, array) => (
					<li
						key={day}
						className={`flex justify-between items-center py-4 ${index !== array.length - 1 && "border-b border-gray-200"}`}
					>
						<span>{days[day]}</span>
						<span>
							{!hours.opening
								? "Fermé"
								: `${hours.opening} - ${hours.closing}`}
						</span>
					</li>
				))}
			</ul>
		</div>
	);
}

export default CompanySchedule;
