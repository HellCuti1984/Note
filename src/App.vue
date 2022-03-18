<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data: function () {
    return {
      del_note: [],
      edit_note: [],

      filter_date_on: false,
      filter_favourite_on: false,
    };
  },
  computed: {
    ...mapGetters(["NOTES"]),
  },
  methods: {
    ...mapActions([
      "GET_NOTES",
      "ADD_NOTE",
      "EDIT_NOTE",
      "ADD_TO_FAVOURITE",
      "DELETE_NOTE",
      "FAVOURITE_FILTER",
      "DATE_FILTER"
    ]),

    INFINITE_SCROLL() {
      window.onscroll = () => {
        let bottomOfWindow =
          document.documentElement.scrollTop + window.innerHeight ===
          document.documentElement.offsetHeight;

        if (bottomOfWindow) {
          alert("kawo");
        }
      };
    },

    SEND_CREATE_NOTE() {
      //это можно сделать лучше
      const noteName = document.getElementById("noteName");
      const noteDesc = document.getElementById("noteDesc");

      if (noteName.value != "" && noteDesc.value != "") {
        this.ADD_NOTE({
          name: noteName.value,
          desc: noteDesc.value,
          favorites: 0,
        });
      } else {
        alert("Заполните все поля!");
        return;
      }

      noteName.value = "";
      noteDesc.value = "";
    },

    //Работа с формой заметок
    SEND_EDIT_NOTE() {
      this.edit_note.name = document.getElementById("noteName").value;
      this.edit_note.desc = document.getElementById("noteDesc").value;
      this.EDIT_NOTE(this.edit_note);
    },

    SEND_DELETE_NOTE(note) {
      this.DELETE_NOTE(note.id);
    },

    CREATE_FORM_FILL: () => {
      document.getElementById("noteFormTitle").innerHTML = "Создать заметку";
      document.getElementById("noteName").value = "";
      document.getElementById("noteDesc").value = "";
    },
    EDIT_FORM_FILL(note) {
      this.edit_note = note;
      document.getElementById("noteFormTitle").innerHTML = "Редактировать";
      document.getElementById("noteName").value = note.name;
      document.getElementById("noteDesc").value = note.desc;
    },

    DELETE_FORM_FILL(note) {
      this.del_note = note;
    },

    FILTER_FAVOURITE(){
      //делать radiobutton правильно
      this.filter_favourite_on ? this.filter_favourite_on = false : this.filter_favourite_on = true
      this.filter_favourite_on ? this.FAVOURITE_FILTER() : this.GET_NOTES()
    },

    FILTER_DATE(){
      //делать radiobutton правильно
      this.filter_date_on ? this.filter_date_on = false : this.filter_date_on = true
      this.filter_date_on ? this.DATE_FILTER() : this.GET_NOTES()
    }
  },

  async mounted() {
    this.GET_NOTES();
    this.INFINITE_SCROLL();
  },
};
</script>

<template>
  <div class="wrapper">
    <div class="content">
      <div class="filter-group">
        <button
          type="button"
          class="btn btn-success"
          data-bs-toggle="modal"
          data-bs-target="#add_red_Note"
          @click="CREATE_FORM_FILL"
        >
          Создать
        </button>
        <h5 class="filter-title" style="color: #fff; padding: 0 10px">
          Фильтры
        </h5>
        <button v-if="filter_favourite_on" type="button" class="btn btn-info" @click="FILTER_FAVOURITE()">
          <i class="fas fa-star"></i>
        </button>
        <button v-else type="button" class="btn btn-warning" @click="FILTER_FAVOURITE()">
          <i class="fas fa-star"></i>
        </button>
        <button type="button" class="btn btn-warning" @click="FILTER_DATE()">
          <i class="fas fa-calendar-alt"></i>
        </button>
      </div>
      <div class="notes">
        <div class="note" v-for="note in NOTES" :key="note.id">
          <div class="title">{{ note.name }}</div>
          <div class="description">{{ note.desc }}</div>
          <div class="noteDate">
            <span class="badge bg-primary">{{
              note.created_at.split("T")[0]
            }}</span>
          </div>
          <div class="buttons-nav">
            <div class="btn-group" role="group" aria-label="Basic example">
              <button
                v-if="note.favorites == 0"
                type="button"
                class="btn btn-info"
                @click="ADD_TO_FAVOURITE(note.id)"
              >
                <i class="fas fa-star"></i>
              </button>
              <button
                v-else
                type="button"
                class="btn btn-warning"
                @click="ADD_TO_FAVOURITE(note.id)"
              >
                <i class="fas fa-star"></i>
              </button>
              <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#add_red_Note"
                @click="EDIT_FORM_FILL(note)"
              >
                Ред.
              </button>
              <button
                type="button"
                class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#delete_node"
                @click="DELETE_FORM_FILL(note)"
              >
                Удалить
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- СОЗДАНИЕ/РЕДАКТИРОВАНИЕ -->
  <div id="modal-area">
    <div
      class="modal fade"
      id="add_red_Note"
      tabindex="-1"
      aria-labelledby="add_red_Note"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="noteFormTitle">Создание заметки</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="noteName" class="form-label">Название заметки</label>
              <input type="text" class="form-control" id="noteName" />
              <div class="valid-feedback">Looks good!</div>
            </div>
            <div class="mb-3">
              <label for="noteDesc" class="form-label">Описание</label>
              <textarea class="form-control" id="noteDesc" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Закрыть
            </button>
            <button
              v-if="edit_note.length == 0"
              type="submit"
              class="btn btn-primary"
              id="liveToastBtn"
              @click="SEND_CREATE_NOTE()"
            >
              Сохранить
            </button>
            <button
              v-else
              type="submit"
              class="btn btn-primary"
              id="liveToastBtn"
              data-bs-dismiss="modal"
              @click="SEND_EDIT_NOTE()"
            >
              Сохранить
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ПРЕДУПРЕЖДЕНИЕ -->
  <div
    class="modal fade"
    id="delete_node"
    tabindex="-1"
    aria-labelledby="delete_node"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="noteFormTitle">
            Удалить заметку: {{ del_note.name }}
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">Вы уверены?</div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Отмена
          </button>
          <button
            type="button"
            class="btn btn-primary"
            data-bs-dismiss="modal"
            @click="SEND_DELETE_NOTE(del_note)"
          >
            Удалить
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ОПОВЕЩЕНИЕ -->
</template>

<style>
@import "@/assets/base.css";

body {
  height: 100vh;
}

#app {
  height: 100%;
}

#app table {
  color: #fff;
}
</style>
