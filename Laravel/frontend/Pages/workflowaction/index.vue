<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Workflow Action Master</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-inner">
                <div>
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">Filter Data</h6>
                        </div>
                        <div class="card-tools">
                            <nuxt-link to="/workflowaction/create" class="btn btn-primary btn-dim"><em class="icon ni ni-plus"></em><span>Add New Data</span></nuxt-link>
                        </div>
                    </div>
                    <form @submit.prevent="fetch(true)" class="gy-3">
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Keyword</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="keyword" v-model="filter.keyword">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0">Status</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <select v-model="filter.is_active" class="form-control">
                                            <option value="">All</option>
                                            <option value="1">Published</option>
                                            <option value="0">Unpublished</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                            </div>
                            <div class="col-lg-10">
                                <button @click="reset" class="btn btn-light" type="button">Reset Filter</button>
                                <button type="submit" class="btn btn-primary">Filter Data</button>
                            </div>
                        </div>
                    </form>
                </div>
                <hr>
                <div>
                    <div class="card-title-group align-start gx-3 mb-3 mt-4">
                        <div class="card-title">
                            <h6 class="title">Latest Workflow Action Created</h6>
                        </div>
                        <div class="card-tools">
                            <div class="drodown">
                                <a
                                href="#"
                                class="dropdown-toggle btn btn-primary btn-dim"
                                data-toggle="dropdown"
                                ><em class="icon ni ni-file-xls"></em
                                ><span>Spreadsheets</span></a
                                >
                                <div class="dropdown-menu dropdown-menu-right">
                                <ul class="link-list-opt no-bdr">
                                    <li>
                                    <a href="#" @click="downloadTemplate"
                                        ><em class="icon ni ni-file-download"></em
                                        ><span>Download Template</span></a
                                    >
                                    </li>
                                    <li>
                                    <a href="#" @click="exportData"
                                        ><em class="icon ni ni-download"></em
                                        ><span>Export Data</span></a
                                    >
                                    </li>
                                    <li>
                                    <a href="#" data-toggle="modal" data-target="#import-data"
                                        ><em class="icon ni ni-upload"></em
                                        ><span>Import Data</span></a
                                    >
                                    </li>
                                </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <table class="table table-tranx mt-4 is-compact">
                            <thead>
                                <tr class="tb-tnx-head">
                                    <th>
                                        <span class="d-md-inline-block d-none">No</span>
                                    </th>
                                    <th>
                                        <span class="d-md-inline-block d-none">Workflow Action Name</span>
                                    </th>
                                    <th>
                                        <span class="d-md-inline-block d-none">Status</span>
                                    </th>
                                    <th>
                                        <span>&nbsp;</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tb-tnx-item" v-for="(workflowaction, index) in data.data" :key="index">
                                    <td>
                                        <span class="d-md-inline-block d-none">{{ index + data.from }}</span>
                                        <div class="d-md-none d-inline-block">
                                            <strong class="text-primary">{{ workflowaction.workflow_action_name }}</strong>
                                            <br>
                                            <span>{{ workflowaction.id }} / {{ workflowaction.workflow_action_name }}</span>
                                            <br>
                                            <Label :active="workflowaction.is_active" />
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-md-inline-block d-none">
                                            <strong class="text-primary">{{ workflowaction.workflow_action_name }}</strong>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-md-inline-block d-none">
                                            <Label :active="workflowaction.is_active" />
                                        </span>
                                    </td>
                                    <td class="tb-tnx-action">
                                        <div class="dropdown">
                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                <ul class="link-list-plain">
                                                    <li><nuxt-link :to="`/workflowaction/detail/${workflowaction.id}`">View</nuxt-link></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-4 d-flex justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <select v-model="filter.show" class="form-control" @change="fetch(true)">
                                    <option value="15">Show 15 Per Page</option>
                                    <option value="25">Show 25 Per Page</option>
                                    <option value="50">Show 50 Per Page</option>
                                    <option value="100">Show 100 Per Page</option>
                                </select>
                            </div>
                            <div class="g">
                                <paginate
                                    v-if="data.current_page"
                                    :page-count="data.last_page"
                                    :page-class="'page-item'"
                                    :page-link-class="'page-link'"
                                    :next-link-class="'page-link'"
                                    :prev-link-class="'page-link'"
                                    :click-handler="changePage"
                                    prev-text='<em class="icon ni ni-chevrons-left"></em>'
                                    next-text='<em class="icon ni ni-chevrons-right"></em>'
                                    :active-class="'active'"
                                    :container-class="'pagination'">
                                </paginate>
                            </div>
                        </div>
                        <span>Showing {{ data.from }} to {{ data.to }} from {{ data.total }} entries</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="import-data">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Import Data</h6>
                        <a href="#" class="close" data-dismiss="modal">
                            <em class="icon ni ni-cross-sm"></em>
                        </a>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card">
                            <div class="card-inner">
                                <div>
                                    <form @submit.prevent="submit" class="gy-3">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                <label class="form-label mb-0" for="keyword">File</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                        <input
                                                            type="file"
                                                            class="custom-file-input"
                                                            id="customFile"
                                                            @change="getDocument()"
                                                            ref="document"/>
                                                        <label
                                                            class="custom-file-label"
                                                            for="customFile">{{document.original_name || "Choose file"}}
                                                        </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2"></div>
                                            <div class="col-lg-10">
                                                <button
                                                    type="button"
                                                    class="btn btn-primary"
                                                    @click="importData">
                                                    Accept
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Label from '@/components/Label';
import { saveAs } from "file-saver";

const DEFAULT_FILTER = {
    keyword: '',
    is_active: '',
    show: 15,
    page: 1
}

export default {
    components: {
        Label
    },
    data () {
        return {
            filter: { ...DEFAULT_FILTER },
            data: {},
            document: {
                document_type_id: null,
                name: "",
                file: null,
                original_name: "",
            },
        };
    },
    methods: {
        reset () {
            this.filter = { ...DEFAULT_FILTER }

            this.fetch(true)
        },
        fetch (resetPage = false) {
            const filter = { ...this.filter }

            if (filter.is_active === '') {
                delete filter.is_active
            }

            if (resetPage) {
                this.resetPage()
                filter.page = 1
            }

            this.$axios.$post('/api/v1/workflowaction/p', filter)
                .then((response) => {
                    this.data = response.data
                })
        },
        changePage (pageNum) {
            this.filter.page = pageNum

            this.fetch()
        },
        resetPage () {
            this.data.current_page = null
        },
        downloadTemplate() {
        this.$axios
            .$get("/api/v1/workflow/template", { responseType: "blob" })
            .then((response) => {
            const blob = new Blob([response]);
            saveAs(blob, "workflow_template.xlsx");
            });
        },
        exportData() {
        this.$axios
            .$get("/api/v1/workflow/export", { responseType: "blob" })
            .then((response) => {
            const blob = new Blob([response]);
            saveAs(blob, "workflow_export.xlsx");
            });
        },
        importData() {
        const formData = new FormData();
        formData.append("file", this.document.file);

        const headers = {
            headers: {
            "Content-Type": "multipart/form-data",
            },
        };

        this.$axios
            .$post(`/api/v1/workflow/import`, formData, headers)
            .then((data) => {
            window.$("#import-data").modal("hide");
            this.fetch();
            });
        },
        getDocument() {
            const file = this.$refs.document.files[0];

            if (file) {
                this.document.file = file;
                this.document.original_name = file.name;
            }
        },
    },
    created () {
        this.fetch()
    }
}
</script>
