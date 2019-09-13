<template>
  <div>
    <h1>Emails</h1>

    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Recipients</th>
          <th>Subject</th>
          <th>Type</th>
          <th>Options</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(email, index) in emails" :key="index">
          <td>{{email.id}}</td>
          <td>{{email.recipients_count}}</td>
          <td>{{email.subject}}</td>
          <td>{{email.type}}</td>
          <td>
            <a :href="'email/'+email.id">View</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Emails",
  data() {
    return {
      emails: []
    };
  },
  created() {
    axios
      .get("/api/emails")
      .then(result => {
        this.emails = result.data.data;
      })
      .catch(error => {
        console.error(error);
      });
  }
};
</script>