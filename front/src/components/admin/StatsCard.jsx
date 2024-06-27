function StatsCard({ title, value, icon, color }) {
	return (
		<div className="bg-white overflow-hidden shadow rounded-lg">
			<div className="p-3 flex items-center h-full">
				<div className="flex-shrink-0 mr-3">
					<div
						className={`flex items-center justify-center h-12 w-12 rounded-lg ${"text-" + color}`}
					>
						{icon}
					</div>
				</div>
				<div>
					<div className="text-sm font-medium text-gray-500 truncate">
						{title}
					</div>
					<dd className="mt-1 text-xl font-semibold text-gray-900">
						{value}
					</dd>
				</div>
			</div>
		</div>
	);
}

export default StatsCard;
