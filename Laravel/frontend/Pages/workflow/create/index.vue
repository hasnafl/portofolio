<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Workflow</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-inner">
                <div>
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">Add New Workflow</h6>
                        </div>
                        <div class="card-tools">
                            <nuxt-link to="/workflow" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="gy-3">
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Workflow Name</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.workflow_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Document Type</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.document_type">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label class="form-label mb-0">Document State</label>
                        <div>
                            <button type="button" class="btn btn-primary" @click="openModal">Add Data</button>
                            <modal v-show="isModalVisible" @close-modal="closeModal" :parentNames="parentNames" :saveType="saveType" :inputType="inputType" @fetchdocstate="fetchdocstate" @fetchtransition="fetchtransition" :processingId="processingId" :editState="editState" :editAction="editAction" :editNextState="editNextState" :editCondition="editCondition" :editAllowSelfApproval="editAllowSelfApproval" :editAllowed="editAllowed" :editAllowEdit="editAllowEdit" :editDocstatus="editDocstatus"/>

                            <table class="table table-tranx mt-4 is-compact">
                                <thead>
                                    <tr class="tb-tnx-head">
                                        <th>
                                            <span class="d-md-inline-block d-none">No</span>
                                        </th>
                                        <th>
                                            <span class="d-md-inline-block d-none">State</span>
                                        </th>
                                        <th>
                                            <span class="d-md-inline-block d-none">Docstatus</span>
                                        </th>
                                        <th>
                                            <span class="d-md-inline-block d-none">Allow Edit</span>
                                        </th>
                                        <th>
                                            <span class="d-md-inline-block d-none">Actions</span>
                                        </th>
                                        <th>
                                            <span>&nbsp;</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tb-tnx-item" v-for="(docstate, index) in docstate" :key="index">
                                        <td>
                                            <span class="d-md-inline-block d-none">{{ index + 1 }}</span>
                                        </td>
                                        <td>
                                            <span class="d-md-inline-block d-none">
                                                <strong class="text-primary">{{ docstate.state }}</strong>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="d-md-inline-block d-none">{{ docstate.docstatus }}</span>
                                        </td>
                                        <td>
                                            <span class="d-md-inline-block d-none">{{ docstate.allow_edit }}</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary btn-sm" @click="openEditDocstateModal(docstate.id, docstate.state, docstate.docstatus, docstate.allow_edit)">Edit</button>
                                            <modal v-show="isModalVisible" @close-modal="closeModal" :parentNames="parentNames" :saveType="saveType" :inputType="inputType" @fetchtransition="fetchtransition" @fetchdocstate="fetchdocstate" :processingId="processingId" :editState="editState" :editDocstatus="editDocstatus" :editAllowEdit="editAllowEdit" :editAction="editAction" :editNextState="editNextState" :editCondition="editCondition" :editAllowSelfApproval="editAllowSelfApproval" :editAllowed="editAllowed"/>
                                            <button type="button" class="btn btn-outline-danger btn-sm" @click="deleteDocstate(docstate.id)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <label class="form-label mb-0">Workflow Transitions</label>
                        <div>
                            <button type="button" class="btn btn-primary" @click="openTransitionModal">Add Data</button>
                            <modal v-show="isModalVisible" @close-modal="closeModal" :parentNames="parentNames" :saveType="saveType" :inputType="inputType" @fetchtransition="fetchtransition" @fetchdocstate="fetchdocstate" :processingId="processingId" :editState="editState" :editAction="editAction" :editNextState="editNextState" :editCondition="editCondition" :editAllowSelfApproval="editAllowSelfApproval" :editAllowed="editAllowed" :editAllowEdit="editAllowEdit" :editDocstatus="editDocstatus"/>

                            <table class="table table-tranx mt-4 is-compact">
                                <thead>
                                    <tr class="tb-tnx-head">
                                        <th>
                                            <span class="d-md-inline-block d-none">No</span>
                                        </th>
                                        <th>
                                            <span class="d-md-inline-block d-none">State</span>
                                        </th>
                                        <th>
                                            <span class="d-md-inline-block d-none">Action</span>
                                        </th>
                                        <th>
                                            <span class="d-md-inline-block d-none">Next State</span>
                                        </th>
                                        <th>
                                            <span class="d-md-inline-block d-none">Condition</span>
                                        </th>
                                        <th>
                                            <span class="d-md-inline-block d-none">Actions</span>
                                        </th>
                                        <th>
                                            <span>&nbsp;</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tb-tnx-item" v-for="(transition, index) in transitions" :key="index">
                                        <td>
                                            <span class="d-md-inline-block d-none">{{ index + 1 }}</span>
                                        </td>
                                        <td>
                                            <span class="d-md-inline-block d-none">
                                                <strong class="text-primary">{{ transition.state }}</strong>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="d-md-inline-block d-none">{{ transition.action }}</span>
                                        </td>
                                        <td>
                                            <span class="d-md-inline-block d-none">{{ transition.next_state }}</span>
                                        </td>
                                        <td>
                                            <span class="d-md-inline-block d-none">{{ transition.condition }}</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary btn-sm" @click="openEditTransitionModal(transition.id, transition.state, transition.action, transition.next_state, transition.condition, transition.allow_self_approval, transition.allowed)">Edit</button>
                                            <modal v-show="isModalVisible" @close-modal="closeModal" :parentNames="parentNames" :saveType="saveType" :inputType="inputType" @fetchtransition="fetchtransition" @fetchdocstate="fetchdocstate" :processingId="processingId" :editState="editState" :editAction="editAction" :editNextState="editNextState" :editCondition="editCondition" :editAllowSelfApproval="editAllowSelfApproval" :editAllowed="editAllowed" :editAllowEdit="editAllowEdit" :editDocstatus="editDocstatus"/>
                                            <button type="button" class="btn btn-outline-danger btn-sm" @click="deleteTransition(transition.id)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-10">
                            </div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
import Editor from '@/components/Editor'
import modal from "@/components/Modal";

export default {
    components: {
        Editor,
        modal,
    },
    data () {
        return {
            data: {
                id: '',
                workflow_name: '',
                document_type: '',
                workflow_state_field: ''
            },
            filter: {
                keyword: '',
                is_active: ''
            },
            docstate: {},
            transitions: {},
            isModalVisible: false,
            parentNames: null,
            saveType: 'tmp',
            inputType: '',
            processingId: '',
            editdata: null,
            editState: '',
            editAction: '',
            editNextState: '',
            editCondition: '',
            editAllowSelfApproval: '',
            editAllowed: '',
            editDocstatus: '',
            editAllowEdit: '',
        }
    },
    async fetch() {
        const returna = await this.$axios.$post(`/api/v1/workflow/tmpworkflow`);
        this.parentNames = returna.data;
        this.fetchdocstate();
        this.fetchtransition();
    },
    methods: {
        openModal() { 
            this.inputType = 'docstate';
            this.isModalVisible = true;
        },
        openTransitionModal() {
            this.inputType = 'transition'
            this.isModalVisible = true;
        },
        openEditTransitionModal(id, state, action, nextstate, condition, selfapproval, allowed) {
            this.processingId = id;
            this.editState = state;
            this.editAction = action;
            this.editNextState = nextstate;
            this.editCondition = condition;
            this.editAllowSelfApproval = selfapproval;
            this.editAllowed = allowed;
            this.inputType = 'edittransition';
            this.isModalVisible = true;
        },
        openEditDocstateModal(id, state, docstatus, allowedit) {
            this.processingId = id;
            this.editState = state;
            this.editDocstatus = docstatus;
            this.editAllowEdit = allowedit;
            this.inputType = 'editdocstate';
            this.isModalVisible = true;
        },
        deleteDocstate(id) {
            this.processingId = id;

            this.$axios.$delete(`/api/v1/workflow/deletetmpdocstate/${this.processingId}`)
                .then((response) => {
                    this.fetchdocstate();
                });
        },
        deleteTransition(id) {
            this.processingId = id;

            this.$axios.$delete(`/api/v1/workflow/deletetmptransition/${this.processingId}`)
                .then((response) => {
                    this.fetchtransition();
                });
        }, 
        closeModal() {
            this.inputType = '';
            this.processingId = '';
            this.editState = '';
            this.editAction = '';
            this.editNextState = '';
            this.editCondition = '';
            this.editAllowSelfApproval = '';
            this.editAllowed = '';
            this.editDocstatus = '';
            this.editAllowEdit = '';
            this.isModalVisible = false;
        },
        fetchdocstate () {
            const filter = { ...this.filter }

            if (filter.is_active === '') {
                delete filter.is_active
            }

            if (filter.keyword === '') {
                filter.keyword = this.parentNames
            }

            this.$axios.$post(`/api/v1/workflow/tmpdocstate`, filter)
                .then((response) => {
                    this.docstate = response.data.data
                })
        },
        fetchtransition () {
            const filter = { ...this.filter }

            if (filter.is_active === '') {
                delete filter.is_active
            }

            if (filter.keyword === '') {
                filter.keyword = this.parentNames
            }

            this.$axios.$post(`/api/v1/workflow/tmptransition`, filter)
                .then((response) => {
                    this.transitions = response.data.data
                })
        },
        submit () {
            this.data.id = this.parentNames;
            const data = { ...this.data, d: new Date().getTime() }
            this.$axios.$put('/api/v1/workflow', data)
                .then((data) => {
                    this.$router.push({ path: `/workflow/detail/${data.data.id}` })
                })
        }
    }
}
</script>

<style scoped>
@import "@/static/flag/css/flag-icon.min.css";
</style>
