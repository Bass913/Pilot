import { useEffect, useState } from "react";
import DashboardLayout from "../../layouts/DashboardLayout";
import { useTranslation } from "react-i18next";
import apiService from "../../services/apiService";
import Table from "../../components/admin/Table";
import entitiesNames from "../../lib/entitiesNames";
import { useLocation } from "react-router-dom";
import Loader from "../../components/Loader";
import { useUser } from "../../hooks/useUser";

function DynamicEntityPage({ model }) {
	const location = useLocation();
	const [data, setData] = useState([]);
	const { t } = useTranslation();
	const [loading, setLoading] = useState(true);
	const [page, setPage] = useState(1);
	const { user } = useUser();

	const fetchData = async () => {
		try {
			let response;
			setLoading(true);
			switch (model) {
				case "request":
					response = await apiService.getCompanies({
						search: null,
						page,
					});
					break;
				case "provider":
					response = await apiService.getCompanies({
						search: null,
						page,
					});
					break;
				case "companyProvider":
					response = await apiService.getAdminCompanies(user.id);
					break;
				case "user":
					response = await apiService.getUsers({ page });
					break;
				case "service":
					response = await apiService.getServices({ page });
					break;
				case "companyService":
					response = await apiService.getCompanyServices({
						companyId: user.companyId,
						page,
					});
					break;
				case "employee":
					response = await apiService.getUsers({ page }); // to change to getEmployees
					break;
				case "companyEmployee":
					response = await apiService.getCompanyEmployees({
						companyId: user.companyId,
						page,
					});
					break;
				case "booking":
					response = await apiService.getBookings({ page });
					break;
				case "companyBooking":
					response = await apiService.getCompanyBookings({
						companyId: user.companyId,
						page,
					});
					break;
				default:
					break;
			}
			setData(response.data);
		} catch (error) {
			console.error("Error while fetching data:", error);
			setData([]);
		} finally {
			setLoading(false);
		}
	};

	useEffect(() => {
		fetchData();
	}, [location.pathname, page]);

	return (
		<DashboardLayout>
			<h1 className="text-xl font-medium text-gray-900 mb-4 capitalize">
				{t(entitiesNames[model].labelPlural)}
			</h1>
			{loading ? (
				<div className="flex justify-center h-96 items-center">
					<Loader />
				</div>
			) : (
				<Table
					data={data}
					model={model}
					page={page}
					onChangePage={setPage}
				/>
			)}
		</DashboardLayout>
	);
}

export default DynamicEntityPage;
