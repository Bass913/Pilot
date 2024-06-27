import { useState, useEffect } from "react";
import DashboardLayout from "../../layouts/DashboardLayout";
import apiService from "../../services/apiService";
import Loader from "../../components/Loader";
import { useTranslation } from "react-i18next";
import StatsCard from "../../components/admin/StatsCard";
import {
	CalendarIcon,
	ChartBarIcon,
	UserGroupIcon,
	UsersIcon,
	CheckCircleIcon,
	XCircleIcon,
	CalendarDaysIcon,
} from "@heroicons/react/24/outline";
import { useUser } from "../../hooks/useUser";

function Dashboard() {
	const [stats, setStats] = useState({});
	const [loading, setLoading] = useState(true);
	const { t } = useTranslation();
	const { user } = useUser();

	const statsToShow = [
		{
			title: t("total-reservations"),
			value: stats.totalReservations,
			color: "blue-500",
			icon: <CalendarIcon className="w-5" />,
		},
		{
			title: t("total-clients"),
			value: stats.totalClients,
			color: "green-600",
			icon: <UsersIcon className="w-5" />,
		},
		{
			title: t("today-reservations"),
			value: stats.todaysReservations,
			color: "orange-600",
			icon: <CalendarDaysIcon className="w-5" />,
		},
		{
			title: t("weekly-reservations"),
			value: stats.weeklyReservations,
			color: "purple-400",
			icon: <CalendarDaysIcon className="w-5" />,
		},
		{
			title: t("monthly-reservations"),
			value: stats.monthlyReservations,
			color: "pink-600",
			icon: <ChartBarIcon className="w-5" />,
		},
		{
			title: t("active-reservations"),
			value: stats.activeReservations,
			color: "green-600",
			icon: <CheckCircleIcon className="w-5" />,
		},
		{
			title: t("cancelled-reservations"),
			value: stats.cancelledReservations,
			color: "red-600",
			icon: <XCircleIcon className="w-5" />,
		},
		{
			title: t("distincts-clients-per-week"),
			value: stats.distinctClientsPerWeek,
			color: "yellow-600",
			icon: <UserGroupIcon className="w-5" />,
		},
		{
			title: t("distincts-services"),
			value: stats.distinctServices,
			color: "blue-600",
			icon: <UserGroupIcon className="w-5" />,
		}
	];

	const fetchStats = async () => {
		try {
			let response;
			if (user.roles.includes("ROLE_SUPERADMIN"))
				response = await apiService.getSuperAdminStatistics();
			else response = await apiService.getAdminStatistics(user.id);
			setStats(response.data);
		} catch (error) {
			console.error("Error fetching stats:", error);
		} finally {
			setLoading(false);
		}
	};

	useEffect(() => {
		fetchStats();
	}, []);

	return (
		<DashboardLayout>
			<h1 className="text-xl font-medium text-gray-900 mb-10 capitalize">
				Dashboard
			</h1>
			{loading ? (
				<div className="flex justify-center h-96 items-center">
					<Loader />
				</div>
			) : (
				<div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
					{statsToShow.map((stat, index) => (
						(stat.value || stat.value === 0) &&
						<StatsCard
							key={index}
							title={stat.title}
							value={stat.value}
							color={stat.color}
							icon={stat.icon}
						/>
					))}
				</div>
			)}
		</DashboardLayout>
	);
}

export default Dashboard;
