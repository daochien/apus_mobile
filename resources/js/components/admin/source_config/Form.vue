<template>
    <div class="row justify-content-center">
        <div class="col-md-12 text-right" style="margin-bottom: 10px;">
            <a href="/admin/packages" class="btn btn-responsive btn-warning">Hủy bỏ</a>
            <button @click.prevent="edit(form.id)" v-if="editModel" type="submit" class="btn btn-responsive btn-info">Cập nhật</button>
            <button @click.prevent="saveForm()" v-else class="btn btn-responsive btn-primary">Tạo mới</button>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="form-group">
                        <label for="input">Chọn source mẫu(*)</label>
                        <select v-model="form.source_id" :class="['form-control', { 'is-invalid': form.errors.has('source_id') }]">
                            <option value="">--Chọn--</option>
                            <option v-for="(item, index) in sources" :value="item.id" :key="index">{{ item.name }}</option>
                        </select>
                        <has-error :form="form" field="source_id"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="input">Tên key(*)</label>
                        <input v-model="form.key" :class="['form-control', { 'is-invalid': form.errors.has('key') }]">
                        <has-error :form="form" field="key"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="input">Loại Group</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="form.is_group" id="status1" value="1">
                                <label class="form-check-label" for="status1">Có</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="form.is_group" id="status2" value="0">
                                <label class="form-check-label" for="status2">Không</label>
                            </div>
                        </div>
                    </div>

                    <template v-if="form.is_group == 0">
                        <div class="form-group">
                            <label for="input">Type(*)</label>
                            <select v-model="form.type" :class="['form-control', { 'is-invalid': form.errors.has('type') }]">
                                <option value="">--Chọn--</option>
                                <option value="string">String</option>
                                <option value="number">Number</option>
                                <option value="radio">Radio</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="file">File</option>
                            </select>
                            <has-error :form="form" field="type"></has-error>
                        </div>

                        <template v-if="form.type === 'string'">
                            <div class="form-group">
                                <label for="input">Value</label>
                                <input v-model="form.value" :class="['form-control']">
                                <!--<has-error :form="form" field="name"></has-error>-->
                            </div>
                        </template>
                        <template v-else-if="form.type === 'number'">
                            <div class="form-group">
                                <label for="input">Value</label>
                                <input type="number" v-model="form.value" :class="['form-control']">
                                <!--<has-error :form="form" field="name"></has-error>-->
                            </div>
                        </template>

                        <template v-else-if="form.type === 'file'">
                            <div class="form-group">
                                <label for="input">Value</label>
                                <input type="file" :class="['form-control']" @change="onSelectConfigFile($event)">
                                <!--<has-error :form="form" field="name"></has-error>-->
                            </div>
                        </template>

                        <template v-else>
                            <div class="form-group">
                                <label for="input">Value</label>
                                <input-tag placeholder="Enter..." v-model="form.value" :limit="10"></input-tag>
                                <!--<has-error :form="form" field="name"></has-error>-->
                            </div>
                        </template>

                        <div class="form-group">
                            <label for="input">Cho phép sửa</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio":value="true" v-model="form.is_edit">
                                    <label class="form-check-label" :for="`is_edit_1`">Có</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" :value="false" v-model="form.is_edit">
                                    <label class="form-check-label" :for="`is_edit_2`">Không</label>
                                </div>
                            </div>
                        </div>

                    </template>
                    <template v-else>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm" @click="addItem()">Thêm config</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table ">
                                        <thead>
                                        <tr style="font-size:13px;">
                                            <th class="border-top-0" scope="col">Key</th>
                                            <th class="border-top-0" scope="col">Type</th>
                                            <th class="border-top-0" scope="col">Value</th>
                                            <th class="border-top-0" scope="col">Can Edit</th>
                                            <th class="border-top-0" scope="col">Tác vụ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item, index) in configItems" :key="index">
                                            <td>
                                                <input class="form-control" v-model="item.key">
                                            </td>
                                            <td>
                                                <select v-model="item.type" :class="['form-control']">
                                                    <option value="">--Chọn--</option>
                                                    <option value="string">String</option>
                                                    <option value="number">Number</option>
                                                    <option value="radio">Radio</option>
                                                    <option value="checkbox">Checkbox</option>
                                                    <option value="file">File</option>
                                                </select>
                                            </td>
                                            <td>
                                                <template v-if="item.type === 'checkbox' || item.type === 'radio'">
                                                    <input-tag placeholder="Enter..." v-model="item.value" :limit="10"></input-tag>
                                                </template>
                                                <template v-else-if="item.type === 'file'">
                                                    <div class="form-group col-md-3">
                                                        <label for="input">Value</label>
                                                        <input type="file" :class="['form-control']" @change="onSelectConfigItemFile($event, index)">
                                                        <!--<has-error :form="form" field="name"></has-error>-->
                                                    </div>
                                                </template>
                                                <template v-else>
                                                    <input v-model="item.value" :class="['form-control']">
                                                </template>
                                            </td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio":value="true" v-model="item.is_edit">
                                                    <label class="form-check-label" :for="`is_edit_item_${index}`">Có</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" :value="false" v-model="item.is_edit">
                                                    <label class="form-check-label" :for="`is_edit_item_${index}`">Không</label>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm " @click="removeGiftItem(index)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

const img_link ="/images/no_image.jpg";

export default {
    props: ['editModel', 'sources', 'config'],
    data () {
        return {
            form: new Form({
                source_id: '',
                is_group: 0,
                key: '',
                type: '',
                value: [],
                is_edit: true,
                configs: []
            }),
            avatarPreview: img_link,
            formUpload: new Form({
                file: ''
            }),
            configItems: []
        }
    },
    created () {
        if (this.editModel) {
            this.loadEdit(this.config);
        }
    },
    methods: {
        onSelectConfigFile (e) {
            try {
                let files = e.target.files || e.dataTransfer.files;

                if (!files.length) return;

                if (/\.(jpe?g|png|gif)$/i.test(files[0].name)) {
                    this.formUpload.file = files[0];
                    this.formUpload.submit('post','/admin/common/upload', {
                        transformRequest: [function (data, headers) {
                            return window.objectToFormData.serialize(data)
                        }],
                    })
                        .then(({data}) => {
                            if (data.status) {
                                this.form.value = data.data.file_name;
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: data.message
                                });
                            }
                        })
                        .catch((error) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Lỗi hệ thống'
                            });
                        })
                } else {
                    this.configs[index].value = "";

                    Toast.fire({
                        icon: "error",
                        title: "Image not valid",
                    });
                }
            } catch (error) {}
        },
        onSelectConfigItemFile (e, index) {
            try {
                let files = e.target.files || e.dataTransfer.files;

                if (!files.length) return;

                if (/\.(jpe?g|png|gif)$/i.test(files[0].name)) {
                    this.formUpload.file = files[0];
                    this.formUpload.submit('post','/admin/common/upload', {
                        transformRequest: [function (data, headers) {
                            return window.objectToFormData.serialize(data)
                        }],
                    })
                        .then(({data}) => {
                            if (data.status) {
                                this.configItems[index].value = data.data.file_name;
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: data.message
                                });
                            }
                        })
                        .catch((error) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Lỗi hệ thống'
                            });
                        })
                } else {
                    this.configs[index].value = "";

                    Toast.fire({
                        icon: "error",
                        title: "Image not valid",
                    });
                }
            } catch (error) {}
        },
        addItem () {
            this.configItems.push({
                key: '',
                type: '',
                value: [],
                is_edit: true
            });
        },
        beforeAddTag (tag) {
            return item;
        },
        removeGiftItem (index) {
            this.configItems.splice(index, 1);
        },

        saveForm () {
            this.form.config_items = JSON.stringify(this.configItems);
            this.form.submit('post', '/admin/source-configs', {
                transformRequest: [function (data, headers) {
                    return window.objectToFormData.serialize(data)
                }],
            })
            .then(({data}) => {
                if (data.status) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Tạo mới source mẫu thành công'
                    });
                    window.location.href = '/admin/source-configs';
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                }

            }).catch((error) => {
            Toast.fire({
                icon: 'error',
                title: 'Lỗi hệ thống'
            });
            })
        },
        loadEdit (config) {
            let that = this;
            this.form.id = config.id;
            this.form.key = config.key;
            this.form.source_id = config.source_id;
            this.form.is_group = config.is_group;
            this.form.type = config.type;
            this.avatarPreview = config.image ? config.image : img_link;
            if (config.items.length > 0 && config.is_group == 1) {
                this.configItems = [];
                config.items.forEach((item) => {
                    let value = '';
                    if (item.type === 'radio' || item.type === 'checkbox') {
                        value = JSON.parse(item.value);
                    } else if (item.type === 'file') {
                        value = '';
                    } else {
                        value = item.value;
                    }
                    that.configItems.push({
                        'id': item.id,
                        'key': item.key,
                        'type': item.type,
                        'value': value,
                        'is_edit': item.is_edit == 1? true: false
                    })
                })
            } else {
                let value;
                if (config.type === 'radio' || config.type === 'checkbox') {
                    value = JSON.parse(config.value);
                } else if (config.type === 'file') {
                    value = '';
                } else {
                    value = config.value;
                }
                this.form.value = value;
            }

        },
        edit (id) {
            this.form.config_items = JSON.stringify(this.configItems);
            this.form.submit('post', '/admin/source-configs/'+id, {
                transformRequest: [function (data, headers) {
                    return window.objectToFormData.serialize(data)
                }],
            })
                .then(({data}) => {
                    let status = data.status ? 'success': 'error';

                    Toast.fire({
                        icon: status,
                        title: data.message
                    });

                    if (data.status)
                        window.location.href = '/admin/source-configs';

                }).catch((error) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Lỗi hệ thống'
                });
            })
        }
    }
}
</script>
