<template>
  <div class="page__title">
    <h1>Your file list</h1>
    <p>Your list of files.<br>From here, you can easily edit, delete, or download your files.</p>

    <div class="page__table">
      <div class="page__table-action">
        <div class="table__search">
          <input type="text" placeholder="Search">
        </div>
      </div>
      <div class="page__table-content">
        <table>
          <tr>
            <th><input type="checkbox"></th>
            <th>Preview</th>
            <th>Name</th>
            <th>Size</th>
            <th>Extension</th>
            <th>Date</th>
            <th></th>
          </tr>
          <tr v-for="file in files" :key="file.id">
            <td><input type="checkbox"></td>
            <th>
              <img v-if="isImage(file.mime)" :src="file.url" alt="Preview"/>
            </th>
            <th>{{ file.name ? file.name : file.original_name }}</th>
            <th>{{ file.size }}</th>
            <th style="text-transform: uppercase">{{ file.mime }}</th>
            <th>{{ file.date }}</th>
            <th>
              <div>
                <button type="button" @click="downloadFile(file.id)">
                  <div class="icon"></div>Download
                </button>
                <button type="button">
                  <div class="icon"></div>Edit
                </button>
                <button type="button">
                  <div class="icon"></div>Delete
                </button>
              </div>
            </th>
          </tr>
        </table>
      </div>
      <div class="page__table-pagination">
        <div class="table__pagination">
          <div class="table__pagination-show">
            <p>Showing {{ pagination.total > 0 ? pagination.start + ' to ' + pagination.end : '0' }} of {{ pagination.total }} results</p>
          </div>
          <div class="table__pagination-page">
            <div>
              <span>Per Page</span>
            </div>
            <div>
              <select @change="changePerPage($event)">
                <option value="5" selected>5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
          </div>
          <div class="table__pagination-list">
            <ul class="pagination">
              <li :class="{ disabled: !pagination.prev }">
                <a href="#" @click.prevent="fetchFiles(pagination.prev)">
                  <div class="icon arrow-left"></div>
                </a>
              </li>
              <li v-for="(page, index) in pagination.pages" :key="index">
                <a href="#" @click.prevent="fetchFiles(page.cursor)">{{ index + 1 }}</a>
              </li>
              <li :class="{ disabled: !pagination.next }">
                <a href="#" @click.prevent="fetchFiles(pagination.next)">
                  <div class="icon arrow-right"></div>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {useDynamicHead} from "@/utils/useDynamicHead.js";
import {onMounted, ref} from "vue";
import {UserApi} from '@/services/api/api.js';

export default {
  setup() {
    useDynamicHead('My files');

    const files = ref([]);
    const pagination = ref({
      next: null,
      prev: null,
      start: 0,
      end: 0,
      total: 0,
      pages: [],
    });
    const perPage = ref(5);

    const isImage = (mime) => {
      return ['jpeg', 'jpg', 'png', 'gif', 'webp'].includes(mime);
    };

    const fetchFiles = async (cursor = 1) => {
      try {
        const response = await fetch(`https://api.live.local/api/v1/file/check?page=${cursor}&per_page=${perPage.value}`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
          },
        });

        const data = await response.json();

        if (!data.data || !data.data.file || !data.data.pagination) {
          throw new Error('Не удалось получить данные файлов или пагинации');
        }

        files.value = data.data.file.file;

        pagination.value = {
          next: data.data.pagination.current_page < data.data.pagination.total_pages ? data.data.pagination.current_page + 1 : null,
          prev: data.data.pagination.current_page > 1 ? data.data.pagination.current_page - 1 : null,
          start: (data.data.pagination.current_page - 1) * data.data.pagination.per_page + 1,
          end: Math.min(data.data.pagination.current_page * data.data.pagination.per_page, data.data.pagination.total_items),
          total: data.data.pagination.total_items,
          pages: Array.from({ length: data.data.pagination.total_pages }, (_, i) => ({
            cursor: i + 1,
          })),
        };

      } catch (error) {
        console.error('Ошибка:', error);
      }
    };

    const changePerPage = (event) => {
      perPage.value = event.target.value;
      fetchFiles();
    };

    const downloadFile = async (fileId) => {
      try {
        const response = await fetch(`https://api.live.local/api/v1/file/${fileId}/download`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
          },
        });

        if (!response.ok) {
          throw new Error('Ошибка при скачивании файла');
        }

        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;

        const contentDisposition = response.headers.get('Content-Disposition');
        a.download = contentDisposition?.match(/filename="?([^"]+)"?/)[1];
        document.body.appendChild(a);
        a.click();
        a.remove();

        window.URL.revokeObjectURL(url);
      } catch (error) {
        console.error('Ошибка при скачивании файла:', error);
      }
    };

    onMounted(async () => {
      await UserApi.register();
      await fetchFiles();
    });

    return {
      files,
      pagination,
      fetchFiles,
      isImage,
      changePerPage,
      downloadFile,
    };
  },
}
</script>
