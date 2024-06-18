import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/outline";
import { useTranslation } from "react-i18next";

function Pagination({ currentPage, totalItems, itemsPerPage, onPageChange }) {
	const { t } = useTranslation();

	const totalPages = Math.ceil(totalItems / itemsPerPage);

	const goToTheNextPage = () => {
		if (currentPage < totalPages) {
			onPageChange(currentPage + 1);
		}
	};

	const goToThePreviousPage = () => {
		if (currentPage > 1) {
			onPageChange(currentPage - 1);
		}
	};

	return (
		<div className="flex justify-between items-center mt-4">
			<div className="flex-1 flex justify-between sm:hidden">
				<button
					type="button"
					className="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-sm text-gray-700 bg-white hover:bg-gray-50"
					onClick={goToThePreviousPage}
					disabled={currentPage === 1}
				>
					{t("previous")}
				</button>
				<button
					type="button"
					className="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-sm text-gray-700 bg-white hover:bg-gray-50"
					onClick={goToTheNextPage}
					disabled={currentPage === totalPages}
				>
					{t("next")}
				</button>
			</div>
			<div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
				<div>
					<p className="text-sm text-gray-700">
						{t("showing")}{" "}
						<span>
							{currentPage > 0
								? (currentPage - 1) * itemsPerPage + 1
								: 0}
						</span>{" "}
						{t("to")}{" "}
						<span>
							{currentPage * itemsPerPage > totalItems
								? totalItems
								: currentPage * itemsPerPage}
						</span>{" "}
						{t("of")} <span>{totalItems}</span> {t("results")}
					</p>
				</div>
				<div>
					<nav
						className="relative z-0 inline-flex rounded-sm shadow-sm -space-x-px"
						aria-label="Pagination"
					>
						<button
							type="button"
							className={`relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 text-sm font-medium text-gray-500 hover:bg-gray-50 ${
								currentPage === 1 ? "bg-gray-50" : "bg-white"
							}`}
							onClick={goToThePreviousPage}
							disabled={currentPage === 1}
						>
							<span className="sr-only">{t("previous")}</span>
							<ChevronLeftIcon className="h-5 w-5" />
						</button>
						<button
							type="button"
							className="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
						>
							{currentPage}
						</button>
						<button
							type="button"
							className={`relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 text-sm font-medium text-gray-500 hover:bg-gray-50 ${
								currentPage === totalPages
									? "bg-gray-50"
									: "bg-white"
							}`}
							onClick={goToTheNextPage}
							disabled={currentPage === totalPages}
						>
							<span className="sr-only">{t("next")}</span>
							<ChevronRightIcon className="h-5 w-5" />
						</button>
					</nav>
				</div>
			</div>
		</div>
	);
}

export default Pagination;
