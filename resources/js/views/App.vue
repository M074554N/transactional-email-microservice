<template>
  <div>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <router-link class="navbar-brand" to="/">
        <img src="/img/logo.png" alt="takeaway.com logo">
      </router-link>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarsExampleDefault"
        aria-controls="navbarsExampleDefault"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/">
              Home
              <span class="sr-only">(current)</span>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/emails">Emails</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/create-email">Create Email</router-link>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container" id="main">
      <router-view></router-view>
    </div>

    <footer>
      <div class="inner">
        <p>
          Made By
          <a href="https://github.com/MohamedHassanWD" target="_blank">Mohamed Hassan</a>.
        </p>
      </div>
    </footer>
  </div>
</template>

<script>
export default {};
</script>

<style>
.navbar-brand img{
  height: 30px;
}
#main {
  padding: 80px 0 0 0;
}
footer {
  text-align: center;
  margin: 30px 0 0 0;
  padding: 20px;
  background: #ffc;
}
</style>