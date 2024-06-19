import React from "react";
import { useTranslation } from "react-i18next";

function KbisModal({ isOpen, onClose, selectedRequest, onAccept, onDecline }) {
	const { t } = useTranslation();

	if (!isOpen) {
		return null;
	}

	return (
		<div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
			<div className="bg-white rounded-md shadow-lg w-11/12 max-w-2xl overflow-hidden">
				<div className="p-5">
					<h2 className="text-lg font-medium text-gray-900 mb-4">
						{t("kbis")}
					</h2>
					{selectedRequest && (
						<iframe
							src={`http://localhost/uploads/kbis/${selectedRequest.kbis}`}
							title="Document Viewer"
							className="w-full h-96 border border-gray-200"
							allowFullScreen={true}
						/>
					)}
					<div className="mt-4 flex justify-end space-x-2">
						<button
							className="rounded-md border border-primary-500 bg-primary-500 text-white px-4 py-2 mr-2 hover:bg-primary-600 shadow-sm px-4 py-2 text-sm font-medium"
							onClick={() => onAccept(selectedRequest)}
						>
							{t("accept")}
						</button>
						<button
							className="rounded-md border border-red-500 bg-red-500 text-white px-4 py-2 hover:bg-red-600 shadow-sm px-4 py-2 text-sm font-medium"
							onClick={() => onDecline(selectedRequest)}
						>
							{t("decline")}
						</button>
					</div>
				</div>
				<div className="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
					<button
						type="button"
						className="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
						onClick={onClose}
					>
						{t("close")}
					</button>
				</div>
			</div>
		</div>
	);
}

export default KbisModal;
