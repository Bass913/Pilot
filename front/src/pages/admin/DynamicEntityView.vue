<script setup>
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import Table from "../../components/Table.vue";
import { onMounted, reactive, ref } from "vue";
import axiosInstance from "@/utils/axiosInstance";
import ViewHeader from "@/components/ViewHeader.vue";
import router from "@/router";

const props = defineProps({
	model: {
		required: true,
		type: String,
	},
});

const state = reactive({
	data: [],
	total: 0,
	count: 0,
	resetPagination: false,
	search: "",
});

onMounted(async () => {
	await fetchData(1);
});

const fetchData = async (page, search = state.search) => {
	const response = await axiosInstance.get(
		`/${props.model}s?page=${page}&limit=10` +
			(search ? `&search=${search}` : "")
	);
	state.data = response.data[`${props.model}s`];
	state.count = response.data.count;
	state.total = response.data.total;
};

const searchData = (search) => {
	state.search = search;
	fetchData(1, search);
	state.resetPagination = !state.resetPagination;
};

const changePage = (page) => {
	fetchData(page);
};

const showDetails = (row) => {
	router.push(`/dash/${props.model}/${row.id}`);
};

</script>

<template>
	<LayoutAuthenticated class="min-h-screen">
		<ViewHeader
			:model="props.model"
			:total="state.total"
			@search="searchData"
		/>
		<div class="p-4">
			<Table
				:model="props.model"
				:data="state.data"
				:total="state.count"
				:resetPagination="state.resetPagination"
				@changePage="changePage"
				@showRow="showDetails"
			/>
		</div>
	</LayoutAuthenticated>
</template>
