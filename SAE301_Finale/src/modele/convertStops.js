import fs from "node:fs";

const txt = fs.readFileSync("stops.txt", "utf8");
const lines = txt.split("\n").filter(l => l.trim() !== "");

const headers = lines[0].split(",");

const data = lines.slice(1).map(line => {
    const values = line.split(",");
    return {
        stop_id: values[headers.indexOf("stop_id")],
        stop_name: values[headers.indexOf("stop_name")],
        lat: parseFloat(values[headers.indexOf("stop_lat")]),
        lon: parseFloat(values[headers.indexOf("stop_lon")])
    };
});

fs.writeFileSync(
    "src/vue/data/stops.json",
    JSON.stringify(data, null, 2)
);

console.log("✅ stops.json généré");
