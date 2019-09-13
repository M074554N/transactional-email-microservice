<template>
  <div>
    <h1>View Email: {{$route.params.id}}</h1>
    <br />
    <div class="row">
      <div class="col">
        <h5>Subject: {{email.subject}}</h5>
      </div>
      <div class="col">
        <h5>Type: {{email.type}}</h5>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <h5>Body:</h5>
        <div v-if="email.type == 'text/html'" v-html="email.body"></div>
        <div v-else>{{email.body}}</div>
      </div>
    </div>
    <p>&nbsp;</p>
    <div class="row">
      <p>
        <em>Recipients: {{email.recipients_count}}</em>
      </p>
      <table class="table table-striped table-hover" v-if="email.recipients.length > 0">
        <thead>
          <tr>
            <th>#</th>
            <th>Recipient</th>
            <th>Status</th>
            <th>Provider</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(recipient, index) in email.recipients" :key="index">
            <td>{{recipient.id}}</td>
            <td>{{recipient.address}}</td>
            <td>
              <span
                class="badge badge-danger"
                v-if="recipient.pivot.status == 'failed'"
              >{{recipient.pivot.status}}</span>
              <span
                class="badge badge-success"
                v-if="recipient.pivot.status == 'processed'"
              >{{recipient.pivot.status}}</span>
            </td>
            <td>{{recipient.pivot.service_provider}}</td>
          </tr>
        </tbody>
      </table>
      <h4
        v-if="email.recipients.length < 1"
      >I don't know how this happend, but it looks like there are no recipients!</h4>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Emails",
  data() {
    return {
      error: null,
      email: null
    };
  },
  created() {
    axios
      .get("/api/emails/" + this.$route.params.id)
      .then(response => {
        this.email = response.data.email;
      })
      .catch(error => {
        this.error = error.message;
      });
  }
};
</script>