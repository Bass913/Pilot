import { useEffect, useState } from "react";
import columnNames from "../../lib/columnNames";
import { selectColumns } from "../../utils/columnsSelector";
import { useTranslation } from "react-i18next";
import { getValue } from "../../utils/tableDataUpdater";
import { useNavigate } from "react-router-dom";
import Pagination from "../Pagination";

function Table({ model, data, page, onChangePage }) {
	const { t } = useTranslation();
	const navigate = useNavigate();

	const [currentPage, setCurrentPage] = useState(page);
	const [columns, setColumns] = useState([]);
	const [dataToShow, setDataToShow] = useState([]);
	const [hoveredRow, setHoveredRow] = useState(null);
	const itemsPerPage = 10;
	const total = data["hydra:totalItems"] || 0;

	useEffect(() => {
		const selectedData =
			selectColumns(model, data["hydra:member"]) || dataToShow;
		setColumns(selectedData.length ? Object.keys(selectedData[0]) : []);
		setDataToShow(selectedData);
	}, [data, page]);

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
													className={`whitespace-nowrap pl-4 pr-3 text-sm sm:pl-6 cursor-pointer ${
														col === "images"
															? "py-1"
															: "py-4"
													}`}
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

					<Pagination
						currentPage={currentPage}
						totalItems={total}
						itemsPerPage={itemsPerPage}
						onPageChange={setCurrentPage}
					/>
				</div>
			</div>
		</div>
	);
}

export default Table;
