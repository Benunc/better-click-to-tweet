/**
 * External Dependencies
 */
const webpack = require("webpack");
const CleanWebpackPlugin = require("clean-webpack-plugin");
const ExtractTextPlugin = require("extract-text-webpack-plugin");

// Enviornment Flag
const inProduction = "production" === process.env.NODE_ENV;

// Editor CSS loader
const editBlocksCSSPlugin = new ExtractTextPlugin({
  filename: "./assets/block/build/editor.css"
});

// Configuration for the ExtractTextPlugin.
const extractConfig = {
  use: [
    { loader: "raw-loader" },
    {
      loader: "postcss-loader",
      options: {
        plugins: [require("autoprefixer")]
      }
    },
    {
      loader: "sass-loader",
      query: {
        outputStyle: inProduction ? "compressed" : "nested"
      }
    }
  ]
};

// Externals
const externals = {
  react: "React"
};
// WordPress dependences
const wpDependencies = [
  "components",
  "element",
  "blocks",
  "editor",
  "hooks",
  "utils",
  "date",
  "data",
  "i18n",
  "editPost",
  "plugins",
  "apiRequest"
];
wpDependencies.forEach(wpDependency => {
  externals["@wordpress/" + wpDependency] = {
    this: ["wp", wpDependency]
  };
});

// Webpack config.
const config = {
  entry: "./assets/block/src/index.js",
  externals,
  output: {
    filename: "./assets/block/build/script.js",
    path: __dirname,
    library: ["bctt", "[name]"],
    libraryTarget: "this"
  },
  resolve: {
    modules: [__dirname, "node_modules"]
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: "babel-loader"
      },
      {
        test: /editor\.s?css$/,
        use: editBlocksCSSPlugin.extract(extractConfig)
      }
    ]
  },
  plugins: [
    new CleanWebpackPlugin(["build"]),
    editBlocksCSSPlugin
  ],
  stats: {
    children: false
  }
};

// For Productions
if (inProduction) {
  config.plugins.push(new webpack.optimize.UglifyJsPlugin({ sourceMap: true }));
  config.plugins.push(new webpack.LoaderOptionsPlugin({ minimize: true }));
}

module.exports = config;
