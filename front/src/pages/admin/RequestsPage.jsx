import { useEffect, useState } from "react";
import DashboardLayout from "../../layouts/DashboardLayout";
import { useTranslation } from "react-i18next";
import apiService from "../../services/apiService";
import KbisModal from "../../components/modals/KbisModal";
import Pagination from "../../components/Pagination";
import Loader from "../../components/Loader";

function RequestsPage() {
	const [requests, setRequests] = useState([]);
	const [isModalOpen, setIsModalOpen] = useState(false);
	const [selectedRequest, setSelectedRequest] = useState(null);
	const [currentPage, setCurrentPage] = useState(1);
	const [totalItems, setTotalItems] = useState(0);
	const [loading, setLoading] = useState(false);
	const itemsPerPage = 10;
	const { t } = useTranslation();

	const fetchRequests = async (page) => {
		try {
			setLoading(true);
			const response = await apiService.getRequests({ page });
			setRequests(response.data["hydra:member"]);
			setTotalItems(response.data["hydra:totalItems"]);
		} catch (error) {
			console.error("Error while fetching requests:", error);
		} finally {
			setLoading(false);
		}
	};

	useEffect(() => {
		console.log("currentPage", currentPage);
		fetchRequests(currentPage);
	}, [currentPage]);

	const openModal = (request) => {
		setSelectedRequest(request);
		setIsModalOpen(true);
	};

	const closeModal = () => {
		setIsModalOpen(false);
		setSelectedRequest(null);
	};

	const acceptRequest = async (request) => {
		try {
			// await apiService.acceptRequest(request);
			closeModal();
			fetchRequests(currentPage);
		} catch (error) {
			console.error("Error accepting request:", error);
		}
	};

	const declineRequest = async (request) => {
		try {
			// await apiService.declineRequest(request);
			closeModal();
			fetchRequests(currentPage);
		} catch (error) {
			console.error("Error declining request:", error);
		}
	};

	const handlePageChange = (page) => {
		setCurrentPage(page);
	};

	return (
		<DashboardLayout>
			<h1 className="text-xl font-medium text-gray-900 mb-4 capitalize">
				{t("requests")}
			</h1>
			{loading ? (
				<div className="flex justify-center items-center h-64">
					<Loader />
				</div>
			) : (
				<div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-8">
					{requests.map((request) => (
						<div
							key={request["@id"]}
							className="bg-white shadow-md rounded-sm p-4 border border-gray-100 cursor-pointer hover:shadow-lg"
							onClick={() => openModal(request)}
						>
							<div className="flex justify-between items-center">
								<div>
									<h2 className="text-lg font-medium text-gray-900">
										{request.firstname} {request.lastname}
									</h2>
									<p className="text-sm text-gray-500">
										{request.phone}
									</p>
									<p className="text-sm text-gray-500">
										{request.email}
									</p>
								</div>
								<button
									onClick={(e) => {
										e.stopPropagation();
										openModal(request);
									}}
									className="text-sm text-primary-500 hover:underline"
								>
									{t("view-kbis")}
								</button>
							</div>
						</div>
					))}
				</div>
			)}

			<Pagination
				currentPage={currentPage}
				totalItems={totalItems}
				itemsPerPage={itemsPerPage}
				onPageChange={handlePageChange}
			/>

			<KbisModal
				isOpen={isModalOpen}
				onClose={closeModal}
				selectedRequest={selectedRequest}
				onAccept={acceptRequest}
				onDecline={declineRequest}
			/>
		</DashboardLayout>
	);
}

export default RequestsPage;
