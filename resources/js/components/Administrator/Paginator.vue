<template>
  <div class="pagination">
    <button @click="prevPage" :disabled="currentPage === 1">Anterior</button>
    <span>{{ currentPage }}</span>
    <button @click="nextPage" :disabled="currentPage === totalPages">Siguiente</button>
  </div>
</template>
<script>
export default {
  props: {
    currentPage: Number,
    totalPages: Number
  },
  methods: {
    prevPage() {
      console.log('Probando evento paginador prev');
      if (this.currentPage > 1) {
        this.$parent.changePage(this.currentPage - 1);
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        console.log('Probando evento paginador prev');
        this.$parent.changePage(this.currentPage + 1);
      }
    }
  }
};
</script>