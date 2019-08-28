<template>
  <div>
    <h1>Create a New Email</h1>
    <br />
    <br />
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="subject">Message Subject:</label>
          <input v-model="subject" type="text" class="form-control" id="subject" />
        </div>
      </div>
      <div class="col">
        <div class="form-group">
          <label for="recipients">Recipients:</label>
          <vue-tags-input
            v-model="tag"
            :validation="validation"
            :allow-edit-tags="true"
            @tags-changed="newTags => recipients = newTags"
            aria-describedby="emailHelp"
          />
          <small
            id="emailHelp"
            class="form-text text-muted"
          >Enter comma separated list of valid email addresses of recipients.</small>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="subject">Message Type:</label>
      <div class="form-check form-check-inline">
        <input
          :checked="type == 'text'"
          v-model="type"
          class="form-check-input"
          type="radio"
          id="text"
          value="text"
        />
        <label class="form-check-label" for="text">Plain Text</label>
      </div>
      <div class="form-check form-check-inline">
        <input
          :checked="type == 'html'"
          v-model="type"
          class="form-check-input"
          type="radio"
          id="html"
          value="html"
        />
        <label class="form-check-label" for="html">HTML</label>
      </div>
      <div class="form-check form-check-inline">
        <input
          :checked="type == 'markdown'"
          v-model="type"
          class="form-check-input"
          type="radio"
          id="markdown"
          value="markdown"
        />
        <label class="form-check-label" for="markdown">Markdown</label>
      </div>
    </div>
    <div class="form-group">
      <label for="subject">Message Content:</label>
      <textarea v-model="body" class="form-control" id cols="30" rows="10"></textarea>
    </div>
    <div class="form-group">
      <button class="btn btn-primary">Send Message</button>
    </div>
  </div>
</template>

<script>
import VueTagsInput from "@johmun/vue-tags-input";
export default {
  name: "CreateEmail",
  data() {
    return {
      subject: "",
      type: "text",
      body: "",
      recipients: "",
      tag: "",
      validation: [
        {
          classes: "min-length",
          rule: tag => tag.text.length < 5,
          disableAdd: true
        },
        {
          classes: "email-address",
          rule: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
          disableAdd: true
        }
      ]
    };
  },
  components: {
    VueTagsInput
  }
};
</script>

<style>
.vue-tags-input {
  background: #324652;
}

.vue-tags-input .ti-new-tag-input {
  background: transparent;
  color: #b7c4c9;
}

.vue-tags-input .ti-input {
  padding: 4px 10px;
  transition: border-bottom 200ms ease;
}

/* we cange the border color if the user focuses the input */
.vue-tags-input.ti-focus .ti-input {
  border: 1px solid #ebde6e;
}

/* some stylings for the autocomplete layer */
.vue-tags-input .ti-autocomplete {
  background: #283944;
  border: 1px solid #8b9396;
  border-top: none;
}

/* the selected item in the autocomplete layer, should be highlighted */
.vue-tags-input .ti-item.ti-selected-item {
  background: #ebde6e;
  color: #283944;
}

/* style the placeholders color across all browser */
.vue-tags-input ::-webkit-input-placeholder {
  color: #a4b1b6;
}

.vue-tags-input ::-moz-placeholder {
  color: #a4b1b6;
}

.vue-tags-input :-ms-input-placeholder {
  color: #a4b1b6;
}

.vue-tags-input :-moz-placeholder {
  color: #a4b1b6;
}

/* default styles for all the tags */
.vue-tags-input .ti-tag {
  position: relative;
  background: #78eb6e;
  color: #283944;
}

/* we defined a custom css class in the data model, now we are using it to style the tag */
.vue-tags-input .ti-tag.custom-class {
  background: transparent;
  border: 1px solid #ebde6e;
  color: #ebde6e;
  margin-right: 4px;
  border-radius: 0px;
  font-size: 13px;
}

/* the styles if a tag is invalid */
.vue-tags-input .ti-tag.ti-invalid {
  background-color: #e88a74;
}

/* if the user input is invalid, the input color should be red */
.vue-tags-input .ti-new-tag-input.ti-invalid {
  color: #e88a74;
}

/* if the user input is valid, the input color should be green */
.vue-tags-input .ti-new-tag-input.ti-valid {
  color: #16a328;
}

/* if a tag or the user input is a duplicate, it should be crossed out */
.vue-tags-input .ti-duplicate span,
.vue-tags-input .ti-new-tag-input.ti-duplicate {
  text-decoration: line-through;
}

/* if the user presses backspace, the complete tag should be crossed out, to mark it for deletion */
.vue-tags-input .ti-tag:after {
  transition: transform 0.2s;
  position: absolute;
  content: "";
  height: 2px;
  width: 108%;
  left: -4%;
  top: calc(50% - 1px);
  background-color: #000;
  transform: scaleX(0);
}

.vue-tags-input .ti-deletion-mark:after {
  transform: scaleX(1);
}
</style>