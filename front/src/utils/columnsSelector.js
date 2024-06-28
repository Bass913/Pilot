import columnsToShow from "../lib/columnsToShow";

export const selectColumns = (model, data) => {
	const columns = columnsToShow[model];
	const selectedData = [];

	if (!data) return [];
	data.forEach((item) => {
		const newItem = {};
		columns.forEach((column) => {
			const columnPath = column.split(".");
			let value = item;
			columnPath.forEach((path) => {
				value = value[path];
			});
			newItem[column] = value;
		});
		selectedData.push(newItem);
	});

	return selectedData;
};
