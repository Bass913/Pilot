import { useEffect, useState } from "react";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/outline";
import columnNames from "../../lib/columnNames";
import { selectColumns } from "../../utils/columnsSelector";
import { useTranslation } from "react-i18next";
import { getValue } from "../../utils/tableDataUpdater";
import { useNavigate } from "react-router-dom";

function Table({ model, data, page, onChangePage }) {
	const { t } = useTranslation();
	const navigate = useNavigate();

	const [currentPage, setCurrentPage] = useState(page);
	const [columns, setColumns] = useState([]);
	const [dataToShow, setDataToShow] = useState([]);
	const [hoveredRow, setHoveredRow] = useState(null);
	const itemsPerPage = 10;
	const total = data["hydra:totalItems"];

	useEffect(() => {
		const selectedData =
			selectColumns(model, data["hydra:member"]) || dataToShow;
		setColumns(selectedData.length ? Object.keys(selectedData[0]) : []);
		setDataToShow(selectedData);
	}, [data, page]);

	const goToTheNextPage = () => {
		setCurrentPage((prevPage) => prevPage + 1);
	};

	const goToThePreviousPage = () => {
		setCurrentPage((prevPage) => prevPage - 1);
	};

	useEffect(() => {
		onChangePage(currentPage);
	}, [currentPage]);

	const showDetails = (index) => {
		const dataItem = data["hydra:member"][index];
		navigate(dataItem["@id"]);
	};

	return (
		<div className="mt-8 flow-root">
			<div className="overflow-x-auto">
				<div className="inline-block min-w-full py-2 align-middle px-1">
					<div className="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-sm">
						<table className="min-w-full divide-y divide-gray-300">
							<thead className="bg-white">
								<tr>
									{columns.map((col) => (
										<th
											key={col}
											scope="col"
											className="py-3.5 pl-4 pr-3 text-left text-gray-900 sm:pl-6 relative uppercase text-xs font-bold"
										>
											<span className="flex items-center space-x-1">
												{columnNames[col]
													? t(columnNames[col])
													: col}
											</span>
										</th>
									))}
								</tr>
							</thead>
							<tbody className="divide-y divide-gray-200 bg-white">
								{dataToShow.length ? (
									dataToShow.map((row, index) => (
										<tr
											key={index}
											onMouseEnter={() =>
												setHoveredRow(index)
											}
											onMouseLeave={() =>
												setHoveredRow(null)
											}
											className={`${
												hoveredRow === index
													? "text-primary-500 bg-gray-50"
													: "text-gray-900"
											}`}
										>
											{columns.map((col) => (
												<td
													key={col}
													className={`whitespace-nowrap pl-4 pr-3 text-sm sm:pl-6 cursor-pointer ${col === "images" ? "py-1" : "py-4"}`}
													onClick={() =>
														showDetails(index)
													}
												>
													{col === "images" ? (
														<img
															src={getValue(
																row,
																col
															)}
															alt="image"
															className="w-20"
														/>
													) : (
														<span>
															{getValue(row, col)}
														</span>
													)}
												</td>
											))}
										</tr>
									))
								) : (
									<tr>
										<td
											colSpan={columns.length}
											className="text-center text-gray-500 text-sm font-semibold py-6"
										>
											Aucune donnée disponible
										</td>
									</tr>
								)}
							</tbody>
						</table>
					</div>

					<div className="flex justify-between items-center mt-4">
						<div className="flex-1 flex justify-between sm:hidden">
							<button
								type="button"
								className="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-sm text-gray-700 bg-white hover:bg-gray-50"
							>
								Previous
							</button>
							<button
								type="button"
								className="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-sm text-gray-700 bg-white hover:bg-gray-50"
							>
								Next
							</button>
						</div>
						<div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
							<div>
								<p className="text-sm text-gray-700">
									Affichage de{" "}
									<span>
										{currentPage > 0
											? (currentPage - 1) * itemsPerPage +
												1
											: 0}
									</span>{" "}
									à{" "}
									<span>
										{currentPage * itemsPerPage > total
											? total
											: currentPage * itemsPerPage}
									</span>{" "}
									parmi <span>{total}</span> résultats
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
											currentPage === 1
												? "bg-gray-50"
												: "bg-white"
										}`}
										onClick={goToThePreviousPage}
										disabled={currentPage === 1}
									>
										<span className="sr-only">
											Précédent
										</span>
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
											currentPage >=
											Math.ceil(total / itemsPerPage)
												? "bg-gray-50"
												: "bg-white"
										}`}
										onClick={goToTheNextPage}
										disabled={
											currentPage >=
											Math.ceil(total / itemsPerPage)
										}
									>
										<span className="sr-only">Suivant</span>
										<ChevronRightIcon className="h-5 w-5" />
									</button>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}

export default Table;
