<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Alamat Pelanggan</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-inner">
                <div>
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">Create New Customer Address</h6>
                        </div>
                        <div class="card-tools">
                            <nuxt-link to="/alamatpelanggan" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="gy-3">
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Pelanggan</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <v-select :options="pelanggan" :reduce="pelanggan => pelanggan" label="customer_name" @search="fetchPelanggan" @input="fillPelanggan"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Label Alamat</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.address_label">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Detail Alamat</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <textarea class="form-control" v-model="data.address_detail"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Keterangan</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.description">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
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

export default {
    components: {
        Editor
    },
    data () {
        return {
            data: {
                id: "",
                address_customercode: "",
                address_label: "",
                address_detail: "",
                description: ""
            },
            pelanggan: []
        }
    },
    methods: {
        fillPelanggan (value) {
            this.data.address_customercode = value.customer_code
        },
        submit () {
            const data = { ...this.data, d: new Date().getTime() }
            this.$axios.$put('/api/v1/alamatpelanggan', data)
                .then((data) => {
                    this.$router.push({ path: `/alamatpelanggan/detail/${data.data.id}` })
                })
        },
        fetchPelanggan (search = '') {
            this.$axios.$get('/api/v1/datapelanggan/', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.pelanggan = response.data
                })
        }
    },
    created () {
        this.fetchPelanggan();
    }
}
</script>

<style scoped>
@import "@/static/flag/css/flag-icon.min.css";
</style>
