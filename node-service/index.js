const express = require("express");
const app = express();

app.get("/ping", (_req, res) => {
  res.json({ service: "node", status: "ok", at: new Date().toISOString() });
});

app.listen(3001, () => console.log("node-mock on :3001"));
