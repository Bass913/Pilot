import { useEffect, useState } from "react";
import DashboardLayout from "../../layouts/DashboardLayout";
import { useTranslation } from "react-i18next";
import apiService from "../../services/apiService";

function RequestsPage() {
	const [requests, setRequests] = useState([]);
	const { t } = useTranslation();

	const fetchRequests = async () => {
		try {
			const response = await apiService.getCompanies({ search: null });
			setRequests(response.data["hydra:member"]);
		} catch (error) {
			console.error(
				"Erreur lors de la récupération des garages :",
				error
			);
		}
	};

	useEffect(() => {
		fetchRequests();
	}, []);

	return (
		<DashboardLayout>
			<h1 className="text-xl font-medium text-gray-900 mb-4 capitalize">
				{t("requests")}
			</h1>
		</DashboardLayout>
	);
}

export default RequestsPage;
