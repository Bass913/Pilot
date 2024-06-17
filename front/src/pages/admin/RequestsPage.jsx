import { useEffect, useState } from "react";
import DashboardLayout from "../../layouts/DashboardLayout";
import { useTranslation } from "react-i18next";
import apiService from "../../services/apiService";

function RequestsPage() {
	const [requests, setRequests] = useState([]);
	const [isModalOpen, setIsModalOpen] = useState(false);
	const [selectedRequest, setSelectedRequest] = useState(null);
	const { t } = useTranslation();

	const fetchRequests = async () => {
		try {
			const response = await apiService.getRequests();
			setRequests(response.data["hydra:member"]);
		} catch (error) {
			console.error("Error fetching requests:", error);
		}
	};

	useEffect(() => {
		fetchRequests();
	}, []);

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
			fetchRequests();
		} catch (error) {
			console.error("Error accepting request:", error);
		}
	};

	const declineRequest = async (request) => {
		try {
			// await apiService.declineRequest(request);
			closeModal();
			fetchRequests();
		} catch (error) {
			console.error("Error declining request:", error);
		}
	};

	return (
		<DashboardLayout>
			<h1 className="text-xl font-medium text-gray-900 mb-4 capitalize">
				{t("requests")}
			</h1>
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
								onClick={() => openModal(request.kbis)}
								className="text-sm text-primary-500 hover:underline"
							>
								{t("view-kbis")}
							</button>
						</div>
					</div>
				))}
			</div>

			{isModalOpen && (
				<div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
					<div className="bg-white rounded-md shadow-lg w-11/12 max-w-2xl overflow-hidden">
						<div className="p-5">
							<h2 className="text-lg font-medium text-gray-900 mb-4">
								{t("kbis")}
							</h2>
							{selectedRequest && (
								<iframe
									src={`https://localhost/uploads/kbis/${selectedRequest.kbis}`}
									title="Document Viewer"
									className="w-full h-96 border border-gray-200"
								/>
							)}
							<div className="mt-4 flex justify-end space-x-2">
								<button
									className="rounded-md border border-primary-500 bg-primary-500 text-white px-4 py-2 mr-2 hover:bg-primary-600 shadow-sm px-4 py-2 bg-white text-sm font-medium"
									onClick={() =>
										acceptRequest(selectedRequest)
									}
								>
									{t("accept")}
								</button>
								<button
									className="rounded-md border border-red-500 bg-red-500 text-white px-4 py-2 hover:bg-red-600 shadow-sm px-4 py-2 bg-white text-sm font-medium"
									onClick={() =>
										declineRequest(selectedRequest)
									}
								>
									{t("decline")}
								</button>
							</div>
						</div>
						<div className="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
							<button
								type="button"
								className="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
								onClick={closeModal}
							>
								{t("close")}
							</button>
						</div>
					</div>
				</div>
			)}
		</DashboardLayout>
	);
}

export default RequestsPage;
