"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

typeof navigator !== "undefined" && function (root, factory) {
  if (typeof define === "function" && define.amd) {
    define(function () {
      return factory(root);
    });
  } else if ((typeof module === "undefined" ? "undefined" : _typeof(module)) === "object" && module.exports) {
    module.exports = factory(root);
  } else {
    root.lottie = factory(root);
    root.bodymovin = root.lottie;
  }
}(window || {}, function (window) {
  "use strict";

  var svgNS = "http://www.w3.org/2000/svg",
      locationHref = "",
      initialDefaultFrame = -999999,
      subframeEnabled = !0,
      idPrefix = "",
      expressionsPlugin,
      isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent),
      cachedColors = {},
      bmRnd,
      bmPow = Math.pow,
      bmSqrt = Math.sqrt,
      bmFloor = Math.floor,
      bmMax = Math.max,
      bmMin = Math.min,
      BMMath = {};

  function ProjectInterface() {
    return {};
  }

  !function () {
    var t,
        e = ["abs", "acos", "acosh", "asin", "asinh", "atan", "atanh", "atan2", "ceil", "cbrt", "expm1", "clz32", "cos", "cosh", "exp", "floor", "fround", "hypot", "imul", "log", "log1p", "log2", "log10", "max", "min", "pow", "random", "round", "sign", "sin", "sinh", "sqrt", "tan", "tanh", "trunc", "E", "LN10", "LN2", "LOG10E", "LOG2E", "PI", "SQRT1_2", "SQRT2"],
        r = e.length;

    for (t = 0; t < r; t += 1) {
      BMMath[e[t]] = Math[e[t]];
    }
  }(), BMMath.random = Math.random, BMMath.abs = function (t) {
    if ("object" === _typeof(t) && t.length) {
      var e,
          r = createSizedArray(t.length),
          i = t.length;

      for (e = 0; e < i; e += 1) {
        r[e] = Math.abs(t[e]);
      }

      return r;
    }

    return Math.abs(t);
  };
  var defaultCurveSegments = 150,
      degToRads = Math.PI / 180,
      roundCorner = .5519;

  function roundValues(t) {
    bmRnd = t ? Math.round : function (t) {
      return t;
    };
  }

  function styleDiv(t) {
    t.style.position = "absolute", t.style.top = 0, t.style.left = 0, t.style.display = "block", t.style.transformOrigin = "0 0", t.style.webkitTransformOrigin = "0 0", t.style.backfaceVisibility = "visible", t.style.webkitBackfaceVisibility = "visible", t.style.transformStyle = "preserve-3d", t.style.webkitTransformStyle = "preserve-3d", t.style.mozTransformStyle = "preserve-3d";
  }

  function BMEnterFrameEvent(t, e, r, i) {
    this.type = t, this.currentTime = e, this.totalTime = r, this.direction = i < 0 ? -1 : 1;
  }

  function BMCompleteEvent(t, e) {
    this.type = t, this.direction = e < 0 ? -1 : 1;
  }

  function BMCompleteLoopEvent(t, e, r, i) {
    this.type = t, this.currentLoop = r, this.totalLoops = e, this.direction = i < 0 ? -1 : 1;
  }

  function BMSegmentStartEvent(t, e, r) {
    this.type = t, this.firstFrame = e, this.totalFrames = r;
  }

  function BMDestroyEvent(t, e) {
    this.type = t, this.target = e;
  }

  function BMRenderFrameErrorEvent(t, e) {
    this.type = "renderFrameError", this.nativeError = t, this.currentTime = e;
  }

  function BMConfigErrorEvent(t) {
    this.type = "configError", this.nativeError = t;
  }

  function BMAnimationConfigErrorEvent(t, e) {
    this.type = t, this.nativeError = e;
  }

  roundValues(!1);
  var createElementID = (F = 0, function () {
    return idPrefix + "__lottie_element_" + (F += 1);
  }),
      F;

  function HSVtoRGB(t, e, r) {
    var i, s, a, n, o, h, l, p;

    switch (h = r * (1 - e), l = r * (1 - (o = 6 * t - (n = Math.floor(6 * t))) * e), p = r * (1 - (1 - o) * e), n % 6) {
      case 0:
        i = r, s = p, a = h;
        break;

      case 1:
        i = l, s = r, a = h;
        break;

      case 2:
        i = h, s = r, a = p;
        break;

      case 3:
        i = h, s = l, a = r;
        break;

      case 4:
        i = p, s = h, a = r;
        break;

      case 5:
        i = r, s = h, a = l;
    }

    return [i, s, a];
  }

  function RGBtoHSV(t, e, r) {
    var i,
        s = Math.max(t, e, r),
        a = Math.min(t, e, r),
        n = s - a,
        o = 0 === s ? 0 : n / s,
        h = s / 255;

    switch (s) {
      case a:
        i = 0;
        break;

      case t:
        i = e - r + n * (e < r ? 6 : 0), i /= 6 * n;
        break;

      case e:
        i = r - t + 2 * n, i /= 6 * n;
        break;

      case r:
        i = t - e + 4 * n, i /= 6 * n;
    }

    return [i, o, h];
  }

  function addSaturationToRGB(t, e) {
    var r = RGBtoHSV(255 * t[0], 255 * t[1], 255 * t[2]);
    return r[1] += e, 1 < r[1] ? r[1] = 1 : r[1] <= 0 && (r[1] = 0), HSVtoRGB(r[0], r[1], r[2]);
  }

  function addBrightnessToRGB(t, e) {
    var r = RGBtoHSV(255 * t[0], 255 * t[1], 255 * t[2]);
    return r[2] += e, 1 < r[2] ? r[2] = 1 : r[2] < 0 && (r[2] = 0), HSVtoRGB(r[0], r[1], r[2]);
  }

  function addHueToRGB(t, e) {
    var r = RGBtoHSV(255 * t[0], 255 * t[1], 255 * t[2]);
    return r[0] += e / 360, 1 < r[0] ? r[0] -= 1 : r[0] < 0 && (r[0] += 1), HSVtoRGB(r[0], r[1], r[2]);
  }

  var rgbToHex = function () {
    var t,
        e,
        i = [];

    for (t = 0; t < 256; t += 1) {
      e = t.toString(16), i[t] = 1 === e.length ? "0" + e : e;
    }

    return function (t, e, r) {
      return t < 0 && (t = 0), e < 0 && (e = 0), r < 0 && (r = 0), "#" + i[t] + i[e] + i[r];
    };
  }();

  function BaseEvent() {}

  BaseEvent.prototype = {
    triggerEvent: function triggerEvent(t, e) {
      if (this._cbs[t]) for (var r = this._cbs[t], i = 0; i < r.length; i += 1) {
        r[i](e);
      }
    },
    addEventListener: function addEventListener(t, e) {
      return this._cbs[t] || (this._cbs[t] = []), this._cbs[t].push(e), function () {
        this.removeEventListener(t, e);
      }.bind(this);
    },
    removeEventListener: function removeEventListener(t, e) {
      if (e) {
        if (this._cbs[t]) {
          for (var r = 0, i = this._cbs[t].length; r < i;) {
            this._cbs[t][r] === e && (this._cbs[t].splice(r, 1), r -= 1, i -= 1), r += 1;
          }

          this._cbs[t].length || (this._cbs[t] = null);
        }
      } else this._cbs[t] = null;
    }
  };

  var createTypedArray = function () {
    function r(t, e) {
      var r,
          i = 0,
          s = [];

      switch (t) {
        case "int16":
        case "uint8c":
          r = 1;
          break;

        default:
          r = 1.1;
      }

      for (i = 0; i < e; i += 1) {
        s.push(r);
      }

      return s;
    }

    return "function" == typeof Uint8ClampedArray && "function" == typeof Float32Array ? function (t, e) {
      return "float32" === t ? new Float32Array(e) : "int16" === t ? new Int16Array(e) : "uint8c" === t ? new Uint8ClampedArray(e) : r(t, e);
    } : r;
  }();

  function createSizedArray(t) {
    return Array.apply(null, {
      length: t
    });
  }

  function createNS(t) {
    return document.createElementNS(svgNS, t);
  }

  function createTag(t) {
    return document.createElement(t);
  }

  function DynamicPropertyContainer() {}

  DynamicPropertyContainer.prototype = {
    addDynamicProperty: function addDynamicProperty(t) {
      -1 === this.dynamicProperties.indexOf(t) && (this.dynamicProperties.push(t), this.container.addDynamicProperty(this), this._isAnimated = !0);
    },
    iterateDynamicProperties: function iterateDynamicProperties() {
      var t;
      this._mdf = !1;
      var e = this.dynamicProperties.length;

      for (t = 0; t < e; t += 1) {
        this.dynamicProperties[t].getValue(), this.dynamicProperties[t]._mdf && (this._mdf = !0);
      }
    },
    initDynamicPropertyContainer: function initDynamicPropertyContainer(t) {
      this.container = t, this.dynamicProperties = [], this._mdf = !1, this._isAnimated = !1;
    }
  };

  var getBlendMode = (Oa = {
    0: "source-over",
    1: "multiply",
    2: "screen",
    3: "overlay",
    4: "darken",
    5: "lighten",
    6: "color-dodge",
    7: "color-burn",
    8: "hard-light",
    9: "soft-light",
    10: "difference",
    11: "exclusion",
    12: "hue",
    13: "saturation",
    14: "color",
    15: "luminosity"
  }, function (t) {
    return Oa[t] || "";
  }),
      Oa,
      lineCapEnum = {
    1: "butt",
    2: "round",
    3: "square"
  },
      lineJoinEnum = {
    1: "miter",
    2: "round",
    3: "bevel"
  },
      Matrix = function () {
    var s = Math.cos,
        a = Math.sin,
        n = Math.tan,
        i = Math.round;

    function t() {
      return this.props[0] = 1, this.props[1] = 0, this.props[2] = 0, this.props[3] = 0, this.props[4] = 0, this.props[5] = 1, this.props[6] = 0, this.props[7] = 0, this.props[8] = 0, this.props[9] = 0, this.props[10] = 1, this.props[11] = 0, this.props[12] = 0, this.props[13] = 0, this.props[14] = 0, this.props[15] = 1, this;
    }

    function e(t) {
      if (0 === t) return this;
      var e = s(t),
          r = a(t);
      return this._t(e, -r, 0, 0, r, e, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
    }

    function r(t) {
      if (0 === t) return this;
      var e = s(t),
          r = a(t);
      return this._t(1, 0, 0, 0, 0, e, -r, 0, 0, r, e, 0, 0, 0, 0, 1);
    }

    function o(t) {
      if (0 === t) return this;
      var e = s(t),
          r = a(t);
      return this._t(e, 0, r, 0, 0, 1, 0, 0, -r, 0, e, 0, 0, 0, 0, 1);
    }

    function h(t) {
      if (0 === t) return this;
      var e = s(t),
          r = a(t);
      return this._t(e, -r, 0, 0, r, e, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
    }

    function l(t, e) {
      return this._t(1, e, t, 1, 0, 0);
    }

    function p(t, e) {
      return this.shear(n(t), n(e));
    }

    function m(t, e) {
      var r = s(e),
          i = a(e);
      return this._t(r, i, 0, 0, -i, r, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1)._t(1, 0, 0, 0, n(t), 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1)._t(r, -i, 0, 0, i, r, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
    }

    function f(t, e, r) {
      return r || 0 === r || (r = 1), 1 === t && 1 === e && 1 === r ? this : this._t(t, 0, 0, 0, 0, e, 0, 0, 0, 0, r, 0, 0, 0, 0, 1);
    }

    function c(t, e, r, i, s, a, n, o, h, l, p, m, f, c, d, u) {
      return this.props[0] = t, this.props[1] = e, this.props[2] = r, this.props[3] = i, this.props[4] = s, this.props[5] = a, this.props[6] = n, this.props[7] = o, this.props[8] = h, this.props[9] = l, this.props[10] = p, this.props[11] = m, this.props[12] = f, this.props[13] = c, this.props[14] = d, this.props[15] = u, this;
    }

    function d(t, e, r) {
      return r = r || 0, 0 !== t || 0 !== e || 0 !== r ? this._t(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, t, e, r, 1) : this;
    }

    function u(t, e, r, i, s, a, n, o, h, l, p, m, f, c, d, u) {
      var y = this.props;
      if (1 === t && 0 === e && 0 === r && 0 === i && 0 === s && 1 === a && 0 === n && 0 === o && 0 === h && 0 === l && 1 === p && 0 === m) return y[12] = y[12] * t + y[15] * f, y[13] = y[13] * a + y[15] * c, y[14] = y[14] * p + y[15] * d, y[15] *= u, this._identityCalculated = !1, this;
      var g = y[0],
          v = y[1],
          b = y[2],
          P = y[3],
          E = y[4],
          x = y[5],
          S = y[6],
          A = y[7],
          C = y[8],
          T = y[9],
          _ = y[10],
          k = y[11],
          D = y[12],
          M = y[13],
          F = y[14],
          w = y[15];
      return y[0] = g * t + v * s + b * h + P * f, y[1] = g * e + v * a + b * l + P * c, y[2] = g * r + v * n + b * p + P * d, y[3] = g * i + v * o + b * m + P * u, y[4] = E * t + x * s + S * h + A * f, y[5] = E * e + x * a + S * l + A * c, y[6] = E * r + x * n + S * p + A * d, y[7] = E * i + x * o + S * m + A * u, y[8] = C * t + T * s + _ * h + k * f, y[9] = C * e + T * a + _ * l + k * c, y[10] = C * r + T * n + _ * p + k * d, y[11] = C * i + T * o + _ * m + k * u, y[12] = D * t + M * s + F * h + w * f, y[13] = D * e + M * a + F * l + w * c, y[14] = D * r + M * n + F * p + w * d, y[15] = D * i + M * o + F * m + w * u, this._identityCalculated = !1, this;
    }

    function y() {
      return this._identityCalculated || (this._identity = !(1 !== this.props[0] || 0 !== this.props[1] || 0 !== this.props[2] || 0 !== this.props[3] || 0 !== this.props[4] || 1 !== this.props[5] || 0 !== this.props[6] || 0 !== this.props[7] || 0 !== this.props[8] || 0 !== this.props[9] || 1 !== this.props[10] || 0 !== this.props[11] || 0 !== this.props[12] || 0 !== this.props[13] || 0 !== this.props[14] || 1 !== this.props[15]), this._identityCalculated = !0), this._identity;
    }

    function g(t) {
      for (var e = 0; e < 16;) {
        if (t.props[e] !== this.props[e]) return !1;
        e += 1;
      }

      return !0;
    }

    function v(t) {
      var e;

      for (e = 0; e < 16; e += 1) {
        t.props[e] = this.props[e];
      }

      return t;
    }

    function b(t) {
      var e;

      for (e = 0; e < 16; e += 1) {
        this.props[e] = t[e];
      }
    }

    function P(t, e, r) {
      return {
        x: t * this.props[0] + e * this.props[4] + r * this.props[8] + this.props[12],
        y: t * this.props[1] + e * this.props[5] + r * this.props[9] + this.props[13],
        z: t * this.props[2] + e * this.props[6] + r * this.props[10] + this.props[14]
      };
    }

    function E(t, e, r) {
      return t * this.props[0] + e * this.props[4] + r * this.props[8] + this.props[12];
    }

    function x(t, e, r) {
      return t * this.props[1] + e * this.props[5] + r * this.props[9] + this.props[13];
    }

    function S(t, e, r) {
      return t * this.props[2] + e * this.props[6] + r * this.props[10] + this.props[14];
    }

    function A() {
      var t = this.props[0] * this.props[5] - this.props[1] * this.props[4],
          e = this.props[5] / t,
          r = -this.props[1] / t,
          i = -this.props[4] / t,
          s = this.props[0] / t,
          a = (this.props[4] * this.props[13] - this.props[5] * this.props[12]) / t,
          n = -(this.props[0] * this.props[13] - this.props[1] * this.props[12]) / t,
          o = new Matrix();
      return o.props[0] = e, o.props[1] = r, o.props[4] = i, o.props[5] = s, o.props[12] = a, o.props[13] = n, o;
    }

    function C(t) {
      return this.getInverseMatrix().applyToPointArray(t[0], t[1], t[2] || 0);
    }

    function T(t) {
      var e,
          r = t.length,
          i = [];

      for (e = 0; e < r; e += 1) {
        i[e] = C(t[e]);
      }

      return i;
    }

    function _(t, e, r) {
      var i = createTypedArray("float32", 6);
      if (this.isIdentity()) i[0] = t[0], i[1] = t[1], i[2] = e[0], i[3] = e[1], i[4] = r[0], i[5] = r[1];else {
        var s = this.props[0],
            a = this.props[1],
            n = this.props[4],
            o = this.props[5],
            h = this.props[12],
            l = this.props[13];
        i[0] = t[0] * s + t[1] * n + h, i[1] = t[0] * a + t[1] * o + l, i[2] = e[0] * s + e[1] * n + h, i[3] = e[0] * a + e[1] * o + l, i[4] = r[0] * s + r[1] * n + h, i[5] = r[0] * a + r[1] * o + l;
      }
      return i;
    }

    function k(t, e, r) {
      return this.isIdentity() ? [t, e, r] : [t * this.props[0] + e * this.props[4] + r * this.props[8] + this.props[12], t * this.props[1] + e * this.props[5] + r * this.props[9] + this.props[13], t * this.props[2] + e * this.props[6] + r * this.props[10] + this.props[14]];
    }

    function D(t, e) {
      if (this.isIdentity()) return t + "," + e;
      var r = this.props;
      return Math.round(100 * (t * r[0] + e * r[4] + r[12])) / 100 + "," + Math.round(100 * (t * r[1] + e * r[5] + r[13])) / 100;
    }

    function M() {
      for (var t = 0, e = this.props, r = "matrix3d("; t < 16;) {
        r += i(1e4 * e[t]) / 1e4, r += 15 === t ? ")" : ",", t += 1;
      }

      return r;
    }

    function F(t) {
      return t < 1e-6 && 0 < t || -1e-6 < t && t < 0 ? i(1e4 * t) / 1e4 : t;
    }

    function w() {
      var t = this.props;
      return "matrix(" + F(t[0]) + "," + F(t[1]) + "," + F(t[4]) + "," + F(t[5]) + "," + F(t[12]) + "," + F(t[13]) + ")";
    }

    return function () {
      this.reset = t, this.rotate = e, this.rotateX = r, this.rotateY = o, this.rotateZ = h, this.skew = p, this.skewFromAxis = m, this.shear = l, this.scale = f, this.setTransform = c, this.translate = d, this.transform = u, this.applyToPoint = P, this.applyToX = E, this.applyToY = x, this.applyToZ = S, this.applyToPointArray = k, this.applyToTriplePoints = _, this.applyToPointStringified = D, this.toCSS = M, this.to2dCSS = w, this.clone = v, this.cloneFromProps = b, this.equals = g, this.inversePoints = T, this.inversePoint = C, this.getInverseMatrix = A, this._t = this.transform, this.isIdentity = y, this._identity = !0, this._identityCalculated = !1, this.props = createTypedArray("float32", 16), this.reset();
    };
  }();

  !function (o, h) {
    var l,
        p = this,
        m = 256,
        f = 6,
        c = "random",
        d = h.pow(m, f),
        u = h.pow(2, 52),
        y = 2 * u,
        g = m - 1;

    function v(t) {
      var e,
          r = t.length,
          n = this,
          i = 0,
          s = n.i = n.j = 0,
          a = n.S = [];

      for (r || (t = [r++]); i < m;) {
        a[i] = i++;
      }

      for (i = 0; i < m; i++) {
        a[i] = a[s = g & s + t[i % r] + (e = a[i])], a[s] = e;
      }

      n.g = function (t) {
        for (var e, r = 0, i = n.i, s = n.j, a = n.S; t--;) {
          e = a[i = g & i + 1], r = r * m + a[g & (a[i] = a[s = g & s + e]) + (a[s] = e)];
        }

        return n.i = i, n.j = s, r;
      };
    }

    function b(t, e) {
      return e.i = t.i, e.j = t.j, e.S = t.S.slice(), e;
    }

    function P(t, e) {
      for (var r, i = t + "", s = 0; s < i.length;) {
        e[g & s] = g & (r ^= 19 * e[g & s]) + i.charCodeAt(s++);
      }

      return E(e);
    }

    function E(t) {
      return String.fromCharCode.apply(0, t);
    }

    h["seed" + c] = function (t, e, r) {
      var i = [],
          s = P(function t(e, r) {
        var i,
            s = [],
            a = _typeof(e);

        if (r && "object" == a) for (i in e) {
          try {
            s.push(t(e[i], r - 1));
          } catch (t) {}
        }
        return s.length ? s : "string" == a ? e : e + "\0";
      }((e = !0 === e ? {
        entropy: !0
      } : e || {}).entropy ? [t, E(o)] : null === t ? function () {
        try {
          if (l) return E(l.randomBytes(m));
          var t = new Uint8Array(m);
          return (p.crypto || p.msCrypto).getRandomValues(t), E(t);
        } catch (t) {
          var e = p.navigator,
              r = e && e.plugins;
          return [+new Date(), p, r, p.screen, E(o)];
        }
      }() : t, 3), i),
          a = new v(i),
          n = function n() {
        for (var t = a.g(f), e = d, r = 0; t < u;) {
          t = (t + r) * m, e *= m, r = a.g(1);
        }

        for (; y <= t;) {
          t /= 2, e /= 2, r >>>= 1;
        }

        return (t + r) / e;
      };

      return n.int32 = function () {
        return 0 | a.g(4);
      }, n.quick = function () {
        return a.g(4) / 4294967296;
      }, n["double"] = n, P(E(a.S), o), (e.pass || r || function (t, e, r, i) {
        return i && (i.S && b(i, a), t.state = function () {
          return b(a, {});
        }), r ? (h[c] = t, e) : t;
      })(n, s, "global" in e ? e.global : this == h, e.state);
    }, P(h.random(), o);
  }([], BMMath);

  var BezierFactory = function () {
    var t = {
      getBezierEasing: function getBezierEasing(t, e, r, i, s) {
        var a = s || ("bez_" + t + "_" + e + "_" + r + "_" + i).replace(/\./g, "p");
        if (o[a]) return o[a];
        var n = new h([t, e, r, i]);
        return o[a] = n;
      }
    },
        o = {};
    var l = 11,
        p = 1 / (l - 1),
        e = "function" == typeof Float32Array;

    function i(t, e) {
      return 1 - 3 * e + 3 * t;
    }

    function s(t, e) {
      return 3 * e - 6 * t;
    }

    function a(t) {
      return 3 * t;
    }

    function m(t, e, r) {
      return ((i(e, r) * t + s(e, r)) * t + a(e)) * t;
    }

    function f(t, e, r) {
      return 3 * i(e, r) * t * t + 2 * s(e, r) * t + a(e);
    }

    function h(t) {
      this._p = t, this._mSampleValues = e ? new Float32Array(l) : new Array(l), this._precomputed = !1, this.get = this.get.bind(this);
    }

    return h.prototype = {
      get: function get(t) {
        var e = this._p[0],
            r = this._p[1],
            i = this._p[2],
            s = this._p[3];
        return this._precomputed || this._precompute(), e === r && i === s ? t : 0 === t ? 0 : 1 === t ? 1 : m(this._getTForX(t), r, s);
      },
      _precompute: function _precompute() {
        var t = this._p[0],
            e = this._p[1],
            r = this._p[2],
            i = this._p[3];
        this._precomputed = !0, t === e && r === i || this._calcSampleValues();
      },
      _calcSampleValues: function _calcSampleValues() {
        for (var t = this._p[0], e = this._p[2], r = 0; r < l; ++r) {
          this._mSampleValues[r] = m(r * p, t, e);
        }
      },
      _getTForX: function _getTForX(t) {
        for (var e = this._p[0], r = this._p[2], i = this._mSampleValues, s = 0, a = 1, n = l - 1; a !== n && i[a] <= t; ++a) {
          s += p;
        }

        var o = s + (t - i[--a]) / (i[a + 1] - i[a]) * p,
            h = f(o, e, r);
        return .001 <= h ? function (t, e, r, i) {
          for (var s = 0; s < 4; ++s) {
            var a = f(e, r, i);
            if (0 === a) return e;
            e -= (m(e, r, i) - t) / a;
          }

          return e;
        }(t, o, e, r) : 0 === h ? o : function (t, e, r, i, s) {
          for (var a, n, o = 0; 0 < (a = m(n = e + (r - e) / 2, i, s) - t) ? r = n : e = n, 1e-7 < Math.abs(a) && ++o < 10;) {
            ;
          }

          return n;
        }(t, s, s + p, e, r);
      }
    }, t;
  }();

  function extendPrototype(t, e) {
    var r,
        i,
        s = t.length;

    for (r = 0; r < s; r += 1) {
      for (var a in i = t[r].prototype) {
        Object.prototype.hasOwnProperty.call(i, a) && (e.prototype[a] = i[a]);
      }
    }
  }

  function getDescriptor(t, e) {
    return Object.getOwnPropertyDescriptor(t, e);
  }

  function createProxyFunction(t) {
    function e() {}

    return e.prototype = t, e;
  }

  function bezFunction() {
    var D = Math;

    function y(t, e, r, i, s, a) {
      var n = t * i + e * s + r * a - s * i - a * t - r * e;
      return -.001 < n && n < .001;
    }

    var p = function p(t, e, r, i) {
      var s,
          a,
          n,
          o,
          h,
          l,
          p = defaultCurveSegments,
          m = 0,
          f = [],
          c = [],
          d = bezierLengthPool.newElement();

      for (n = r.length, s = 0; s < p; s += 1) {
        for (h = s / (p - 1), a = l = 0; a < n; a += 1) {
          o = bmPow(1 - h, 3) * t[a] + 3 * bmPow(1 - h, 2) * h * r[a] + 3 * (1 - h) * bmPow(h, 2) * i[a] + bmPow(h, 3) * e[a], f[a] = o, null !== c[a] && (l += bmPow(f[a] - c[a], 2)), c[a] = f[a];
        }

        l && (m += l = bmSqrt(l)), d.percents[s] = h, d.lengths[s] = m;
      }

      return d.addedLength = m, d;
    };

    function g(t) {
      this.segmentLength = 0, this.points = new Array(t);
    }

    function v(t, e) {
      this.partialLength = t, this.point = e;
    }

    var b,
        t = (b = {}, function (t, e, r, i) {
      var s = (t[0] + "_" + t[1] + "_" + e[0] + "_" + e[1] + "_" + r[0] + "_" + r[1] + "_" + i[0] + "_" + i[1]).replace(/\./g, "p");

      if (!b[s]) {
        var a,
            n,
            o,
            h,
            l,
            p,
            m,
            f = defaultCurveSegments,
            c = 0,
            d = null;
        2 === t.length && (t[0] !== e[0] || t[1] !== e[1]) && y(t[0], t[1], e[0], e[1], t[0] + r[0], t[1] + r[1]) && y(t[0], t[1], e[0], e[1], e[0] + i[0], e[1] + i[1]) && (f = 2);
        var u = new g(f);

        for (o = r.length, a = 0; a < f; a += 1) {
          for (m = createSizedArray(o), l = a / (f - 1), n = p = 0; n < o; n += 1) {
            h = bmPow(1 - l, 3) * t[n] + 3 * bmPow(1 - l, 2) * l * (t[n] + r[n]) + 3 * (1 - l) * bmPow(l, 2) * (e[n] + i[n]) + bmPow(l, 3) * e[n], m[n] = h, null !== d && (p += bmPow(m[n] - d[n], 2));
          }

          c += p = bmSqrt(p), u.points[a] = new v(p, m), d = m;
        }

        u.segmentLength = c, b[s] = u;
      }

      return b[s];
    });

    function M(t, e) {
      var r = e.percents,
          i = e.lengths,
          s = r.length,
          a = bmFloor((s - 1) * t),
          n = t * e.addedLength,
          o = 0;
      if (a === s - 1 || 0 === a || n === i[a]) return r[a];

      for (var h = i[a] > n ? -1 : 1, l = !0; l;) {
        if (i[a] <= n && i[a + 1] > n ? (o = (n - i[a]) / (i[a + 1] - i[a]), l = !1) : a += h, a < 0 || s - 1 <= a) {
          if (a === s - 1) return r[a];
          l = !1;
        }
      }

      return r[a] + (r[a + 1] - r[a]) * o;
    }

    var F = createTypedArray("float32", 8);
    return {
      getSegmentsLength: function getSegmentsLength(t) {
        var e,
            r = segmentsLengthPool.newElement(),
            i = t.c,
            s = t.v,
            a = t.o,
            n = t.i,
            o = t._length,
            h = r.lengths,
            l = 0;

        for (e = 0; e < o - 1; e += 1) {
          h[e] = p(s[e], s[e + 1], a[e], n[e + 1]), l += h[e].addedLength;
        }

        return i && o && (h[e] = p(s[e], s[0], a[e], n[0]), l += h[e].addedLength), r.totalLength = l, r;
      },
      getNewSegment: function getNewSegment(t, e, r, i, s, a, n) {
        s < 0 ? s = 0 : 1 < s && (s = 1);

        var o,
            h = M(s, n),
            l = M(a = 1 < a ? 1 : a, n),
            p = t.length,
            m = 1 - h,
            f = 1 - l,
            c = m * m * m,
            d = h * m * m * 3,
            u = h * h * m * 3,
            y = h * h * h,
            g = m * m * f,
            v = h * m * f + m * h * f + m * m * l,
            b = h * h * f + m * h * l + h * m * l,
            P = h * h * l,
            E = m * f * f,
            x = h * f * f + m * l * f + m * f * l,
            S = h * l * f + m * l * l + h * f * l,
            A = h * l * l,
            C = f * f * f,
            T = l * f * f + f * l * f + f * f * l,
            _ = l * l * f + f * l * l + l * f * l,
            k = l * l * l;

        for (o = 0; o < p; o += 1) {
          F[4 * o] = D.round(1e3 * (c * t[o] + d * r[o] + u * i[o] + y * e[o])) / 1e3, F[4 * o + 1] = D.round(1e3 * (g * t[o] + v * r[o] + b * i[o] + P * e[o])) / 1e3, F[4 * o + 2] = D.round(1e3 * (E * t[o] + x * r[o] + S * i[o] + A * e[o])) / 1e3, F[4 * o + 3] = D.round(1e3 * (C * t[o] + T * r[o] + _ * i[o] + k * e[o])) / 1e3;
        }

        return F;
      },
      getPointInSegment: function getPointInSegment(t, e, r, i, s, a) {
        var n = M(s, a),
            o = 1 - n;
        return [D.round(1e3 * (o * o * o * t[0] + (n * o * o + o * n * o + o * o * n) * r[0] + (n * n * o + o * n * n + n * o * n) * i[0] + n * n * n * e[0])) / 1e3, D.round(1e3 * (o * o * o * t[1] + (n * o * o + o * n * o + o * o * n) * r[1] + (n * n * o + o * n * n + n * o * n) * i[1] + n * n * n * e[1])) / 1e3];
      },
      buildBezierData: t,
      pointOnLine2D: y,
      pointOnLine3D: function pointOnLine3D(t, e, r, i, s, a, n, o, h) {
        if (0 === r && 0 === a && 0 === h) return y(t, e, i, s, n, o);
        var l,
            p = D.sqrt(D.pow(i - t, 2) + D.pow(s - e, 2) + D.pow(a - r, 2)),
            m = D.sqrt(D.pow(n - t, 2) + D.pow(o - e, 2) + D.pow(h - r, 2)),
            f = D.sqrt(D.pow(n - i, 2) + D.pow(o - s, 2) + D.pow(h - a, 2));
        return -1e-4 < (l = m < p ? f < p ? p - m - f : f - m - p : m < f ? f - m - p : m - p - f) && l < 1e-4;
      }
    };
  }

  !function () {
    for (var s = 0, t = ["ms", "moz", "webkit", "o"], e = 0; e < t.length && !window.requestAnimationFrame; ++e) {
      window.requestAnimationFrame = window[t[e] + "RequestAnimationFrame"], window.cancelAnimationFrame = window[t[e] + "CancelAnimationFrame"] || window[t[e] + "CancelRequestAnimationFrame"];
    }

    window.requestAnimationFrame || (window.requestAnimationFrame = function (t) {
      var e = new Date().getTime(),
          r = Math.max(0, 16 - (e - s)),
          i = setTimeout(function () {
        t(e + r);
      }, r);
      return s = e + r, i;
    }), window.cancelAnimationFrame || (window.cancelAnimationFrame = function (t) {
      clearTimeout(t);
    });
  }();
  var bez = bezFunction();

  function dataFunctionManager() {
    function m(t, e, r) {
      var i,
          s,
          a,
          n,
          o,
          h,
          l = t.length;

      for (s = 0; s < l; s += 1) {
        if ("ks" in (i = t[s]) && !i.completed) {
          if (i.completed = !0, i.tt && (t[s - 1].td = i.tt), i.hasMask) {
            var p = i.masksProperties;

            for (n = p.length, a = 0; a < n; a += 1) {
              if (p[a].pt.k.i) d(p[a].pt.k);else for (h = p[a].pt.k.length, o = 0; o < h; o += 1) {
                p[a].pt.k[o].s && d(p[a].pt.k[o].s[0]), p[a].pt.k[o].e && d(p[a].pt.k[o].e[0]);
              }
            }
          }

          0 === i.ty ? (i.layers = f(i.refId, e), m(i.layers, e, r)) : 4 === i.ty ? c(i.shapes) : 5 === i.ty && u(i);
        }
      }
    }

    function f(t, e) {
      for (var r = 0, i = e.length; r < i;) {
        if (e[r].id === t) return e[r].layers.__used ? JSON.parse(JSON.stringify(e[r].layers)) : (e[r].layers.__used = !0, e[r].layers);
        r += 1;
      }

      return null;
    }

    function c(t) {
      var e, r, i;

      for (e = t.length - 1; 0 <= e; e -= 1) {
        if ("sh" === t[e].ty) {
          if (t[e].ks.k.i) d(t[e].ks.k);else for (i = t[e].ks.k.length, r = 0; r < i; r += 1) {
            t[e].ks.k[r].s && d(t[e].ks.k[r].s[0]), t[e].ks.k[r].e && d(t[e].ks.k[r].e[0]);
          }
        } else "gr" === t[e].ty && c(t[e].it);
      }
    }

    function d(t) {
      var e,
          r = t.i.length;

      for (e = 0; e < r; e += 1) {
        t.i[e][0] += t.v[e][0], t.i[e][1] += t.v[e][1], t.o[e][0] += t.v[e][0], t.o[e][1] += t.v[e][1];
      }
    }

    function o(t, e) {
      var r = e ? e.split(".") : [100, 100, 100];
      return t[0] > r[0] || !(r[0] > t[0]) && (t[1] > r[1] || !(r[1] > t[1]) && (t[2] > r[2] || !(r[2] > t[2]) && null));
    }

    var h,
        r = function () {
      var i = [4, 4, 14];

      function s(t) {
        var e,
            r,
            i,
            s = t.length;

        for (e = 0; e < s; e += 1) {
          5 === t[e].ty && (r = t[e], void 0, i = r.t.d, r.t.d = {
            k: [{
              s: i,
              t: 0
            }]
          });
        }
      }

      return function (t) {
        if (o(i, t.v) && (s(t.layers), t.assets)) {
          var e,
              r = t.assets.length;

          for (e = 0; e < r; e += 1) {
            t.assets[e].layers && s(t.assets[e].layers);
          }
        }
      };
    }(),
        i = (h = [4, 7, 99], function (t) {
      if (t.chars && !o(h, t.v)) {
        var e,
            r,
            i,
            s,
            a,
            n = t.chars.length;

        for (e = 0; e < n; e += 1) {
          if (t.chars[e].data && t.chars[e].data.shapes) for (i = (a = t.chars[e].data.shapes[0].it).length, r = 0; r < i; r += 1) {
            (s = a[r].ks.k).__converted || (d(a[r].ks.k), s.__converted = !0);
          }
        }
      }
    }),
        s = function () {
      var i = [4, 1, 9];

      function a(t) {
        var e,
            r,
            i,
            s = t.length;

        for (e = 0; e < s; e += 1) {
          if ("gr" === t[e].ty) a(t[e].it);else if ("fl" === t[e].ty || "st" === t[e].ty) if (t[e].c.k && t[e].c.k[0].i) for (i = t[e].c.k.length, r = 0; r < i; r += 1) {
            t[e].c.k[r].s && (t[e].c.k[r].s[0] /= 255, t[e].c.k[r].s[1] /= 255, t[e].c.k[r].s[2] /= 255, t[e].c.k[r].s[3] /= 255), t[e].c.k[r].e && (t[e].c.k[r].e[0] /= 255, t[e].c.k[r].e[1] /= 255, t[e].c.k[r].e[2] /= 255, t[e].c.k[r].e[3] /= 255);
          } else t[e].c.k[0] /= 255, t[e].c.k[1] /= 255, t[e].c.k[2] /= 255, t[e].c.k[3] /= 255;
        }
      }

      function s(t) {
        var e,
            r = t.length;

        for (e = 0; e < r; e += 1) {
          4 === t[e].ty && a(t[e].shapes);
        }
      }

      return function (t) {
        if (o(i, t.v) && (s(t.layers), t.assets)) {
          var e,
              r = t.assets.length;

          for (e = 0; e < r; e += 1) {
            t.assets[e].layers && s(t.assets[e].layers);
          }
        }
      };
    }(),
        a = function () {
      var i = [4, 4, 18];

      function l(t) {
        var e, r, i;

        for (e = t.length - 1; 0 <= e; e -= 1) {
          if ("sh" === t[e].ty) {
            if (t[e].ks.k.i) t[e].ks.k.c = t[e].closed;else for (i = t[e].ks.k.length, r = 0; r < i; r += 1) {
              t[e].ks.k[r].s && (t[e].ks.k[r].s[0].c = t[e].closed), t[e].ks.k[r].e && (t[e].ks.k[r].e[0].c = t[e].closed);
            }
          } else "gr" === t[e].ty && l(t[e].it);
        }
      }

      function s(t) {
        var e,
            r,
            i,
            s,
            a,
            n,
            o = t.length;

        for (r = 0; r < o; r += 1) {
          if ((e = t[r]).hasMask) {
            var h = e.masksProperties;

            for (s = h.length, i = 0; i < s; i += 1) {
              if (h[i].pt.k.i) h[i].pt.k.c = h[i].cl;else for (n = h[i].pt.k.length, a = 0; a < n; a += 1) {
                h[i].pt.k[a].s && (h[i].pt.k[a].s[0].c = h[i].cl), h[i].pt.k[a].e && (h[i].pt.k[a].e[0].c = h[i].cl);
              }
            }
          }

          4 === e.ty && l(e.shapes);
        }
      }

      return function (t) {
        if (o(i, t.v) && (s(t.layers), t.assets)) {
          var e,
              r = t.assets.length;

          for (e = 0; e < r; e += 1) {
            t.assets[e].layers && s(t.assets[e].layers);
          }
        }
      };
    }();

    function u(t) {
      0 !== t.t.a.length || "m" in t.t.p || (t.singleShape = !0);
    }

    var t = {
      completeData: function completeData(t, e) {
        t.__complete || (s(t), r(t), i(t), a(t), m(t.layers, t.assets, e), t.__complete = !0);
      }
    };
    return t.checkColors = s, t.checkChars = i, t.checkShapes = a, t.completeLayers = m, t;
  }

  var dataManager = dataFunctionManager();

  function getFontProperties(t) {
    for (var e = t.fStyle ? t.fStyle.split(" ") : [], r = "normal", i = "normal", s = e.length, a = 0; a < s; a += 1) {
      switch (e[a].toLowerCase()) {
        case "italic":
          i = "italic";
          break;

        case "bold":
          r = "700";
          break;

        case "black":
          r = "900";
          break;

        case "medium":
          r = "500";
          break;

        case "regular":
        case "normal":
          r = "400";
          break;

        case "light":
        case "thin":
          r = "200";
      }
    }

    return {
      style: i,
      weight: t.fWeight || r
    };
  }

  var FontManager = function () {
    var a = {
      w: 0,
      size: 0,
      shapes: []
    },
        e = [];
    e = e.concat([2304, 2305, 2306, 2307, 2362, 2363, 2364, 2364, 2366, 2367, 2368, 2369, 2370, 2371, 2372, 2373, 2374, 2375, 2376, 2377, 2378, 2379, 2380, 2381, 2382, 2383, 2387, 2388, 2389, 2390, 2391, 2402, 2403]);
    var i = ["d83cdffb", "d83cdffc", "d83cdffd", "d83cdffe", "d83cdfff"],
        r = [65039, 8205];

    function f(t, e) {
      var r = createTag("span");
      r.setAttribute("aria-hidden", !0), r.style.fontFamily = e;
      var i = createTag("span");
      i.innerText = "giItT1WQy@!-/#", r.style.position = "absolute", r.style.left = "-10000px", r.style.top = "-10000px", r.style.fontSize = "300px", r.style.fontVariant = "normal", r.style.fontStyle = "normal", r.style.fontWeight = "normal", r.style.letterSpacing = "0", r.appendChild(i), document.body.appendChild(r);
      var s = i.offsetWidth;
      return i.style.fontFamily = function (t) {
        var e,
            r = t.split(","),
            i = r.length,
            s = [];

        for (e = 0; e < i; e += 1) {
          "sans-serif" !== r[e] && "monospace" !== r[e] && s.push(r[e]);
        }

        return s.join(",");
      }(t) + ", " + e, {
        node: i,
        w: s,
        parent: r
      };
    }

    function c(t, e) {
      var r = createNS("text");
      r.style.fontSize = "100px";
      var i = getFontProperties(e);
      return r.setAttribute("font-family", e.fFamily), r.setAttribute("font-style", i.style), r.setAttribute("font-weight", i.weight), r.textContent = "1", e.fClass ? (r.style.fontFamily = "inherit", r.setAttribute("class", e.fClass)) : r.style.fontFamily = e.fFamily, t.appendChild(r), createTag("canvas").getContext("2d").font = e.fWeight + " " + e.fStyle + " 100px " + e.fFamily, r;
    }

    var t = function t() {
      this.fonts = [], this.chars = null, this.typekitLoaded = 0, this.isLoaded = !1, this._warned = !1, this.initTime = Date.now(), this.setIsLoadedBinded = this.setIsLoaded.bind(this), this.checkLoadedFontsBinded = this.checkLoadedFonts.bind(this);
    };

    return t.isModifier = function (t, e) {
      var r = t.toString(16) + e.toString(16);
      return -1 !== i.indexOf(r);
    }, t.isZeroWidthJoiner = function (t, e) {
      return e ? t === r[0] && e === r[1] : t === r[1];
    }, t.isCombinedCharacter = function (t) {
      return -1 !== e.indexOf(t);
    }, t.prototype = {
      addChars: function addChars(t) {
        if (t) {
          var e;
          this.chars || (this.chars = []);
          var r,
              i,
              s = t.length,
              a = this.chars.length;

          for (e = 0; e < s; e += 1) {
            for (r = 0, i = !1; r < a;) {
              this.chars[r].style === t[e].style && this.chars[r].fFamily === t[e].fFamily && this.chars[r].ch === t[e].ch && (i = !0), r += 1;
            }

            i || (this.chars.push(t[e]), a += 1);
          }
        }
      },
      addFonts: function addFonts(t, e) {
        if (t) {
          if (this.chars) return this.isLoaded = !0, void (this.fonts = t.list);
          var r,
              i = t.list,
              s = i.length,
              a = s;

          for (r = 0; r < s; r += 1) {
            var n,
                o,
                h = !0;

            if (i[r].loaded = !1, i[r].monoCase = f(i[r].fFamily, "monospace"), i[r].sansCase = f(i[r].fFamily, "sans-serif"), i[r].fPath) {
              if ("p" === i[r].fOrigin || 3 === i[r].origin) {
                if (0 < (n = document.querySelectorAll('style[f-forigin="p"][f-family="' + i[r].fFamily + '"], style[f-origin="3"][f-family="' + i[r].fFamily + '"]')).length && (h = !1), h) {
                  var l = createTag("style");
                  l.setAttribute("f-forigin", i[r].fOrigin), l.setAttribute("f-origin", i[r].origin), l.setAttribute("f-family", i[r].fFamily), l.type = "text/css", l.innerText = "@font-face {font-family: " + i[r].fFamily + "; font-style: normal; src: url('" + i[r].fPath + "');}", e.appendChild(l);
                }
              } else if ("g" === i[r].fOrigin || 1 === i[r].origin) {
                for (n = document.querySelectorAll('link[f-forigin="g"], link[f-origin="1"]'), o = 0; o < n.length; o += 1) {
                  -1 !== n[o].href.indexOf(i[r].fPath) && (h = !1);
                }

                if (h) {
                  var p = createTag("link");
                  p.setAttribute("f-forigin", i[r].fOrigin), p.setAttribute("f-origin", i[r].origin), p.type = "text/css", p.rel = "stylesheet", p.href = i[r].fPath, document.body.appendChild(p);
                }
              } else if ("t" === i[r].fOrigin || 2 === i[r].origin) {
                for (n = document.querySelectorAll('script[f-forigin="t"], script[f-origin="2"]'), o = 0; o < n.length; o += 1) {
                  i[r].fPath === n[o].src && (h = !1);
                }

                if (h) {
                  var m = createTag("link");
                  m.setAttribute("f-forigin", i[r].fOrigin), m.setAttribute("f-origin", i[r].origin), m.setAttribute("rel", "stylesheet"), m.setAttribute("href", i[r].fPath), e.appendChild(m);
                }
              }
            } else i[r].loaded = !0, a -= 1;

            i[r].helper = c(e, i[r]), i[r].cache = {}, this.fonts.push(i[r]);
          }

          0 === a ? this.isLoaded = !0 : setTimeout(this.checkLoadedFonts.bind(this), 100);
        } else this.isLoaded = !0;
      },
      getCharData: function getCharData(t, e, r) {
        for (var i = 0, s = this.chars.length; i < s;) {
          if (this.chars[i].ch === t && this.chars[i].style === e && this.chars[i].fFamily === r) return this.chars[i];
          i += 1;
        }

        return ("string" == typeof t && 13 !== t.charCodeAt(0) || !t) && console && console.warn && !this._warned && (this._warned = !0, console.warn("Missing character from exported characters list: ", t, e, r)), a;
      },
      getFontByName: function getFontByName(t) {
        for (var e = 0, r = this.fonts.length; e < r;) {
          if (this.fonts[e].fName === t) return this.fonts[e];
          e += 1;
        }

        return this.fonts[0];
      },
      measureText: function measureText(t, e, r) {
        var i = this.getFontByName(e),
            s = t.charCodeAt(0);

        if (!i.cache[s + 1]) {
          var a = i.helper;

          if (" " === t) {
            a.textContent = "|" + t + "|";
            var n = a.getComputedTextLength();
            a.textContent = "||";
            var o = a.getComputedTextLength();
            i.cache[s + 1] = (n - o) / 100;
          } else a.textContent = t, i.cache[s + 1] = a.getComputedTextLength() / 100;
        }

        return i.cache[s + 1] * r;
      },
      checkLoadedFonts: function checkLoadedFonts() {
        var t,
            e,
            r,
            i = this.fonts.length,
            s = i;

        for (t = 0; t < i; t += 1) {
          this.fonts[t].loaded ? s -= 1 : "n" === this.fonts[t].fOrigin || 0 === this.fonts[t].origin ? this.fonts[t].loaded = !0 : (e = this.fonts[t].monoCase.node, r = this.fonts[t].monoCase.w, e.offsetWidth !== r ? (s -= 1, this.fonts[t].loaded = !0) : (e = this.fonts[t].sansCase.node, r = this.fonts[t].sansCase.w, e.offsetWidth !== r && (s -= 1, this.fonts[t].loaded = !0)), this.fonts[t].loaded && (this.fonts[t].sansCase.parent.parentNode.removeChild(this.fonts[t].sansCase.parent), this.fonts[t].monoCase.parent.parentNode.removeChild(this.fonts[t].monoCase.parent)));
        }

        0 !== s && Date.now() - this.initTime < 5e3 ? setTimeout(this.checkLoadedFontsBinded, 20) : setTimeout(this.setIsLoadedBinded, 10);
      },
      setIsLoaded: function setIsLoaded() {
        this.isLoaded = !0;
      }
    }, t;
  }(),
      PropertyFactory = function () {
    var m = initialDefaultFrame,
        s = Math.abs;

    function f(t, e) {
      var r,
          i = this.offsetTime;
      "multidimensional" === this.propType && (r = createTypedArray("float32", this.pv.length));

      for (var s, a, n, o, h, l, p, m, f = e.lastIndex, c = f, d = this.keyframes.length - 1, u = !0; u;) {
        if (s = this.keyframes[c], a = this.keyframes[c + 1], c === d - 1 && t >= a.t - i) {
          s.h && (s = a), f = 0;
          break;
        }

        if (a.t - i > t) {
          f = c;
          break;
        }

        c < d - 1 ? c += 1 : (f = 0, u = !1);
      }

      var y,
          g,
          v,
          b,
          P,
          E,
          x,
          S,
          A,
          C,
          T = a.t - i,
          _ = s.t - i;

      if (s.to) {
        s.bezierData || (s.bezierData = bez.buildBezierData(s.s, a.s || s.e, s.to, s.ti));
        var k = s.bezierData;

        if (T <= t || t < _) {
          var D = T <= t ? k.points.length - 1 : 0;

          for (o = k.points[D].point.length, n = 0; n < o; n += 1) {
            r[n] = k.points[D].point[n];
          }
        } else {
          s.__fnct ? m = s.__fnct : (m = BezierFactory.getBezierEasing(s.o.x, s.o.y, s.i.x, s.i.y, s.n).get, s.__fnct = m), h = m((t - _) / (T - _));
          var M,
              F = k.segmentLength * h,
              w = e.lastFrame < t && e._lastKeyframeIndex === c ? e._lastAddedLength : 0;

          for (p = e.lastFrame < t && e._lastKeyframeIndex === c ? e._lastPoint : 0, u = !0, l = k.points.length; u;) {
            if (w += k.points[p].partialLength, 0 === F || 0 === h || p === k.points.length - 1) {
              for (o = k.points[p].point.length, n = 0; n < o; n += 1) {
                r[n] = k.points[p].point[n];
              }

              break;
            }

            if (w <= F && F < w + k.points[p + 1].partialLength) {
              for (M = (F - w) / k.points[p + 1].partialLength, o = k.points[p].point.length, n = 0; n < o; n += 1) {
                r[n] = k.points[p].point[n] + (k.points[p + 1].point[n] - k.points[p].point[n]) * M;
              }

              break;
            }

            p < l - 1 ? p += 1 : u = !1;
          }

          e._lastPoint = p, e._lastAddedLength = w - k.points[p].partialLength, e._lastKeyframeIndex = c;
        }
      } else {
        var I, V, B, R, L;
        if (d = s.s.length, y = a.s || s.e, this.sh && 1 !== s.h) {
          if (T <= t) r[0] = y[0], r[1] = y[1], r[2] = y[2];else if (t <= _) r[0] = s.s[0], r[1] = s.s[1], r[2] = s.s[2];else {
            var G = N(s.s),
                z = N(y);
            g = r, v = function (t, e, r) {
              var i,
                  s,
                  a,
                  n,
                  o,
                  h = [],
                  l = t[0],
                  p = t[1],
                  m = t[2],
                  f = t[3],
                  c = e[0],
                  d = e[1],
                  u = e[2],
                  y = e[3];
              (s = l * c + p * d + m * u + f * y) < 0 && (s = -s, c = -c, d = -d, u = -u, y = -y);
              o = 1e-6 < 1 - s ? (i = Math.acos(s), a = Math.sin(i), n = Math.sin((1 - r) * i) / a, Math.sin(r * i) / a) : (n = 1 - r, r);
              return h[0] = n * l + o * c, h[1] = n * p + o * d, h[2] = n * m + o * u, h[3] = n * f + o * y, h;
            }(G, z, (t - _) / (T - _)), b = v[0], P = v[1], E = v[2], x = v[3], S = Math.atan2(2 * P * x - 2 * b * E, 1 - 2 * P * P - 2 * E * E), A = Math.asin(2 * b * P + 2 * E * x), C = Math.atan2(2 * b * x - 2 * P * E, 1 - 2 * b * b - 2 * E * E), g[0] = S / degToRads, g[1] = A / degToRads, g[2] = C / degToRads;
          }
        } else for (c = 0; c < d; c += 1) {
          1 !== s.h && (h = T <= t ? 1 : t < _ ? 0 : (s.o.x.constructor === Array ? (s.__fnct || (s.__fnct = []), s.__fnct[c] ? m = s.__fnct[c] : (I = void 0 === s.o.x[c] ? s.o.x[0] : s.o.x[c], V = void 0 === s.o.y[c] ? s.o.y[0] : s.o.y[c], B = void 0 === s.i.x[c] ? s.i.x[0] : s.i.x[c], R = void 0 === s.i.y[c] ? s.i.y[0] : s.i.y[c], m = BezierFactory.getBezierEasing(I, V, B, R).get, s.__fnct[c] = m)) : s.__fnct ? m = s.__fnct : (I = s.o.x, V = s.o.y, B = s.i.x, R = s.i.y, m = BezierFactory.getBezierEasing(I, V, B, R).get, s.__fnct = m), m((t - _) / (T - _)))), y = a.s || s.e, L = 1 === s.h ? s.s[c] : s.s[c] + (y[c] - s.s[c]) * h, "multidimensional" === this.propType ? r[c] = L : r = L;
        }
      }

      return e.lastIndex = f, r;
    }

    function N(t) {
      var e = t[0] * degToRads,
          r = t[1] * degToRads,
          i = t[2] * degToRads,
          s = Math.cos(e / 2),
          a = Math.cos(r / 2),
          n = Math.cos(i / 2),
          o = Math.sin(e / 2),
          h = Math.sin(r / 2),
          l = Math.sin(i / 2);
      return [o * h * n + s * a * l, o * a * n + s * h * l, s * h * n - o * a * l, s * a * n - o * h * l];
    }

    function c() {
      var t = this.comp.renderedFrame - this.offsetTime,
          e = this.keyframes[0].t - this.offsetTime,
          r = this.keyframes[this.keyframes.length - 1].t - this.offsetTime;

      if (!(t === this._caching.lastFrame || this._caching.lastFrame !== m && (this._caching.lastFrame >= r && r <= t || this._caching.lastFrame < e && t < e))) {
        this._caching.lastFrame >= t && (this._caching._lastKeyframeIndex = -1, this._caching.lastIndex = 0);
        var i = this.interpolateValue(t, this._caching);
        this.pv = i;
      }

      return this._caching.lastFrame = t, this.pv;
    }

    function d(t) {
      var e;
      if ("unidimensional" === this.propType) e = t * this.mult, 1e-5 < s(this.v - e) && (this.v = e, this._mdf = !0);else for (var r = 0, i = this.v.length; r < i;) {
        e = t[r] * this.mult, 1e-5 < s(this.v[r] - e) && (this.v[r] = e, this._mdf = !0), r += 1;
      }
    }

    function u() {
      if (this.elem.globalData.frameId !== this.frameId && this.effectsSequence.length) if (this.lock) this.setVValue(this.pv);else {
        var t;
        this.lock = !0, this._mdf = this._isFirstFrame;
        var e = this.effectsSequence.length,
            r = this.kf ? this.pv : this.data.k;

        for (t = 0; t < e; t += 1) {
          r = this.effectsSequence[t](r);
        }

        this.setVValue(r), this._isFirstFrame = !1, this.lock = !1, this.frameId = this.elem.globalData.frameId;
      }
    }

    function y(t) {
      this.effectsSequence.push(t), this.container.addDynamicProperty(this);
    }

    function n(t, e, r, i) {
      this.propType = "unidimensional", this.mult = r || 1, this.data = e, this.v = r ? e.k * r : e.k, this.pv = e.k, this._mdf = !1, this.elem = t, this.container = i, this.comp = t.comp, this.k = !1, this.kf = !1, this.vel = 0, this.effectsSequence = [], this._isFirstFrame = !0, this.getValue = u, this.setVValue = d, this.addEffect = y;
    }

    function o(t, e, r, i) {
      var s;
      this.propType = "multidimensional", this.mult = r || 1, this.data = e, this._mdf = !1, this.elem = t, this.container = i, this.comp = t.comp, this.k = !1, this.kf = !1, this.frameId = -1;
      var a = e.k.length;

      for (this.v = createTypedArray("float32", a), this.pv = createTypedArray("float32", a), this.vel = createTypedArray("float32", a), s = 0; s < a; s += 1) {
        this.v[s] = e.k[s] * this.mult, this.pv[s] = e.k[s];
      }

      this._isFirstFrame = !0, this.effectsSequence = [], this.getValue = u, this.setVValue = d, this.addEffect = y;
    }

    function h(t, e, r, i) {
      this.propType = "unidimensional", this.keyframes = e.k, this.offsetTime = t.data.st, this.frameId = -1, this._caching = {
        lastFrame: m,
        lastIndex: 0,
        value: 0,
        _lastKeyframeIndex: -1
      }, this.k = !0, this.kf = !0, this.data = e, this.mult = r || 1, this.elem = t, this.container = i, this.comp = t.comp, this.v = m, this.pv = m, this._isFirstFrame = !0, this.getValue = u, this.setVValue = d, this.interpolateValue = f, this.effectsSequence = [c.bind(this)], this.addEffect = y;
    }

    function l(t, e, r, i) {
      var s;
      this.propType = "multidimensional";
      var a,
          n,
          o,
          h,
          l = e.k.length;

      for (s = 0; s < l - 1; s += 1) {
        e.k[s].to && e.k[s].s && e.k[s + 1] && e.k[s + 1].s && (a = e.k[s].s, n = e.k[s + 1].s, o = e.k[s].to, h = e.k[s].ti, (2 === a.length && (a[0] !== n[0] || a[1] !== n[1]) && bez.pointOnLine2D(a[0], a[1], n[0], n[1], a[0] + o[0], a[1] + o[1]) && bez.pointOnLine2D(a[0], a[1], n[0], n[1], n[0] + h[0], n[1] + h[1]) || 3 === a.length && (a[0] !== n[0] || a[1] !== n[1] || a[2] !== n[2]) && bez.pointOnLine3D(a[0], a[1], a[2], n[0], n[1], n[2], a[0] + o[0], a[1] + o[1], a[2] + o[2]) && bez.pointOnLine3D(a[0], a[1], a[2], n[0], n[1], n[2], n[0] + h[0], n[1] + h[1], n[2] + h[2])) && (e.k[s].to = null, e.k[s].ti = null), a[0] === n[0] && a[1] === n[1] && 0 === o[0] && 0 === o[1] && 0 === h[0] && 0 === h[1] && (2 === a.length || a[2] === n[2] && 0 === o[2] && 0 === h[2]) && (e.k[s].to = null, e.k[s].ti = null));
      }

      this.effectsSequence = [c.bind(this)], this.data = e, this.keyframes = e.k, this.offsetTime = t.data.st, this.k = !0, this.kf = !0, this._isFirstFrame = !0, this.mult = r || 1, this.elem = t, this.container = i, this.comp = t.comp, this.getValue = u, this.setVValue = d, this.interpolateValue = f, this.frameId = -1;
      var p = e.k[0].s.length;

      for (this.v = createTypedArray("float32", p), this.pv = createTypedArray("float32", p), s = 0; s < p; s += 1) {
        this.v[s] = m, this.pv[s] = m;
      }

      this._caching = {
        lastFrame: m,
        lastIndex: 0,
        value: createTypedArray("float32", p)
      }, this.addEffect = y;
    }

    return {
      getProp: function getProp(t, e, r, i, s) {
        var a;
        if (e.k.length) {
          if ("number" == typeof e.k[0]) a = new o(t, e, i, s);else switch (r) {
            case 0:
              a = new h(t, e, i, s);
              break;

            case 1:
              a = new l(t, e, i, s);
          }
        } else a = new n(t, e, i, s);
        return a.effectsSequence.length && s.addDynamicProperty(a), a;
      }
    };
  }(),
      TransformPropertyFactory = function () {
    var n = [0, 0];

    function i(t, e, r) {
      if (this.elem = t, this.frameId = -1, this.propType = "transform", this.data = e, this.v = new Matrix(), this.pre = new Matrix(), this.appliedTransformations = 0, this.initDynamicPropertyContainer(r || t), e.p && e.p.s ? (this.px = PropertyFactory.getProp(t, e.p.x, 0, 0, this), this.py = PropertyFactory.getProp(t, e.p.y, 0, 0, this), e.p.z && (this.pz = PropertyFactory.getProp(t, e.p.z, 0, 0, this))) : this.p = PropertyFactory.getProp(t, e.p || {
        k: [0, 0, 0]
      }, 1, 0, this), e.rx) {
        if (this.rx = PropertyFactory.getProp(t, e.rx, 0, degToRads, this), this.ry = PropertyFactory.getProp(t, e.ry, 0, degToRads, this), this.rz = PropertyFactory.getProp(t, e.rz, 0, degToRads, this), e.or.k[0].ti) {
          var i,
              s = e.or.k.length;

          for (i = 0; i < s; i += 1) {
            e.or.k[i].to = null, e.or.k[i].ti = null;
          }
        }

        this.or = PropertyFactory.getProp(t, e.or, 1, degToRads, this), this.or.sh = !0;
      } else this.r = PropertyFactory.getProp(t, e.r || {
        k: 0
      }, 0, degToRads, this);

      e.sk && (this.sk = PropertyFactory.getProp(t, e.sk, 0, degToRads, this), this.sa = PropertyFactory.getProp(t, e.sa, 0, degToRads, this)), this.a = PropertyFactory.getProp(t, e.a || {
        k: [0, 0, 0]
      }, 1, 0, this), this.s = PropertyFactory.getProp(t, e.s || {
        k: [100, 100, 100]
      }, 1, .01, this), e.o ? this.o = PropertyFactory.getProp(t, e.o, 0, .01, t) : this.o = {
        _mdf: !1,
        v: 1
      }, this._isDirty = !0, this.dynamicProperties.length || this.getValue(!0);
    }

    return i.prototype = {
      applyToMatrix: function applyToMatrix(t) {
        var e = this._mdf;
        this.iterateDynamicProperties(), this._mdf = this._mdf || e, this.a && t.translate(-this.a.v[0], -this.a.v[1], this.a.v[2]), this.s && t.scale(this.s.v[0], this.s.v[1], this.s.v[2]), this.sk && t.skewFromAxis(-this.sk.v, this.sa.v), this.r ? t.rotate(-this.r.v) : t.rotateZ(-this.rz.v).rotateY(this.ry.v).rotateX(this.rx.v).rotateZ(-this.or.v[2]).rotateY(this.or.v[1]).rotateX(this.or.v[0]), this.data.p.s ? this.data.p.z ? t.translate(this.px.v, this.py.v, -this.pz.v) : t.translate(this.px.v, this.py.v, 0) : t.translate(this.p.v[0], this.p.v[1], -this.p.v[2]);
      },
      getValue: function getValue(t) {
        if (this.elem.globalData.frameId !== this.frameId) {
          if (this._isDirty && (this.precalculateMatrix(), this._isDirty = !1), this.iterateDynamicProperties(), this._mdf || t) {
            var e;

            if (this.v.cloneFromProps(this.pre.props), this.appliedTransformations < 1 && this.v.translate(-this.a.v[0], -this.a.v[1], this.a.v[2]), this.appliedTransformations < 2 && this.v.scale(this.s.v[0], this.s.v[1], this.s.v[2]), this.sk && this.appliedTransformations < 3 && this.v.skewFromAxis(-this.sk.v, this.sa.v), this.r && this.appliedTransformations < 4 ? this.v.rotate(-this.r.v) : !this.r && this.appliedTransformations < 4 && this.v.rotateZ(-this.rz.v).rotateY(this.ry.v).rotateX(this.rx.v).rotateZ(-this.or.v[2]).rotateY(this.or.v[1]).rotateX(this.or.v[0]), this.autoOriented) {
              var r, i;
              if (e = this.elem.globalData.frameRate, this.p && this.p.keyframes && this.p.getValueAtTime) i = this.p._caching.lastFrame + this.p.offsetTime <= this.p.keyframes[0].t ? (r = this.p.getValueAtTime((this.p.keyframes[0].t + .01) / e, 0), this.p.getValueAtTime(this.p.keyframes[0].t / e, 0)) : this.p._caching.lastFrame + this.p.offsetTime >= this.p.keyframes[this.p.keyframes.length - 1].t ? (r = this.p.getValueAtTime(this.p.keyframes[this.p.keyframes.length - 1].t / e, 0), this.p.getValueAtTime((this.p.keyframes[this.p.keyframes.length - 1].t - .05) / e, 0)) : (r = this.p.pv, this.p.getValueAtTime((this.p._caching.lastFrame + this.p.offsetTime - .01) / e, this.p.offsetTime));else if (this.px && this.px.keyframes && this.py.keyframes && this.px.getValueAtTime && this.py.getValueAtTime) {
                r = [], i = [];
                var s = this.px,
                    a = this.py;
                s._caching.lastFrame + s.offsetTime <= s.keyframes[0].t ? (r[0] = s.getValueAtTime((s.keyframes[0].t + .01) / e, 0), r[1] = a.getValueAtTime((a.keyframes[0].t + .01) / e, 0), i[0] = s.getValueAtTime(s.keyframes[0].t / e, 0), i[1] = a.getValueAtTime(a.keyframes[0].t / e, 0)) : s._caching.lastFrame + s.offsetTime >= s.keyframes[s.keyframes.length - 1].t ? (r[0] = s.getValueAtTime(s.keyframes[s.keyframes.length - 1].t / e, 0), r[1] = a.getValueAtTime(a.keyframes[a.keyframes.length - 1].t / e, 0), i[0] = s.getValueAtTime((s.keyframes[s.keyframes.length - 1].t - .01) / e, 0), i[1] = a.getValueAtTime((a.keyframes[a.keyframes.length - 1].t - .01) / e, 0)) : (r = [s.pv, a.pv], i[0] = s.getValueAtTime((s._caching.lastFrame + s.offsetTime - .01) / e, s.offsetTime), i[1] = a.getValueAtTime((a._caching.lastFrame + a.offsetTime - .01) / e, a.offsetTime));
              } else r = i = n;
              this.v.rotate(-Math.atan2(r[1] - i[1], r[0] - i[0]));
            }

            this.data.p && this.data.p.s ? this.data.p.z ? this.v.translate(this.px.v, this.py.v, -this.pz.v) : this.v.translate(this.px.v, this.py.v, 0) : this.v.translate(this.p.v[0], this.p.v[1], -this.p.v[2]);
          }

          this.frameId = this.elem.globalData.frameId;
        }
      },
      precalculateMatrix: function precalculateMatrix() {
        if (!this.a.k && (this.pre.translate(-this.a.v[0], -this.a.v[1], this.a.v[2]), this.appliedTransformations = 1, !this.s.effectsSequence.length)) {
          if (this.pre.scale(this.s.v[0], this.s.v[1], this.s.v[2]), this.appliedTransformations = 2, this.sk) {
            if (this.sk.effectsSequence.length || this.sa.effectsSequence.length) return;
            this.pre.skewFromAxis(-this.sk.v, this.sa.v), this.appliedTransformations = 3;
          }

          this.r ? this.r.effectsSequence.length || (this.pre.rotate(-this.r.v), this.appliedTransformations = 4) : this.rz.effectsSequence.length || this.ry.effectsSequence.length || this.rx.effectsSequence.length || this.or.effectsSequence.length || (this.pre.rotateZ(-this.rz.v).rotateY(this.ry.v).rotateX(this.rx.v).rotateZ(-this.or.v[2]).rotateY(this.or.v[1]).rotateX(this.or.v[0]), this.appliedTransformations = 4);
        }
      },
      autoOrient: function autoOrient() {}
    }, extendPrototype([DynamicPropertyContainer], i), i.prototype.addDynamicProperty = function (t) {
      this._addDynamicProperty(t), this.elem.addDynamicProperty(t), this._isDirty = !0;
    }, i.prototype._addDynamicProperty = DynamicPropertyContainer.prototype.addDynamicProperty, {
      getTransformProperty: function getTransformProperty(t, e, r) {
        return new i(t, e, r);
      }
    };
  }();

  function ShapePath() {
    this.c = !1, this._length = 0, this._maxLength = 8, this.v = createSizedArray(this._maxLength), this.o = createSizedArray(this._maxLength), this.i = createSizedArray(this._maxLength);
  }

  ShapePath.prototype.setPathData = function (t, e) {
    this.c = t, this.setLength(e);

    for (var r = 0; r < e;) {
      this.v[r] = pointPool.newElement(), this.o[r] = pointPool.newElement(), this.i[r] = pointPool.newElement(), r += 1;
    }
  }, ShapePath.prototype.setLength = function (t) {
    for (; this._maxLength < t;) {
      this.doubleArrayLength();
    }

    this._length = t;
  }, ShapePath.prototype.doubleArrayLength = function () {
    this.v = this.v.concat(createSizedArray(this._maxLength)), this.i = this.i.concat(createSizedArray(this._maxLength)), this.o = this.o.concat(createSizedArray(this._maxLength)), this._maxLength *= 2;
  }, ShapePath.prototype.setXYAt = function (t, e, r, i, s) {
    var a;

    switch (this._length = Math.max(this._length, i + 1), this._length >= this._maxLength && this.doubleArrayLength(), r) {
      case "v":
        a = this.v;
        break;

      case "i":
        a = this.i;
        break;

      case "o":
        a = this.o;
        break;

      default:
        a = [];
    }

    (!a[i] || a[i] && !s) && (a[i] = pointPool.newElement()), a[i][0] = t, a[i][1] = e;
  }, ShapePath.prototype.setTripleAt = function (t, e, r, i, s, a, n, o) {
    this.setXYAt(t, e, "v", n, o), this.setXYAt(r, i, "o", n, o), this.setXYAt(s, a, "i", n, o);
  }, ShapePath.prototype.reverse = function () {
    var t = new ShapePath();
    t.setPathData(this.c, this._length);
    var e = this.v,
        r = this.o,
        i = this.i,
        s = 0;
    this.c && (t.setTripleAt(e[0][0], e[0][1], i[0][0], i[0][1], r[0][0], r[0][1], 0, !1), s = 1);
    var a,
        n = this._length - 1,
        o = this._length;

    for (a = s; a < o; a += 1) {
      t.setTripleAt(e[n][0], e[n][1], i[n][0], i[n][1], r[n][0], r[n][1], a, !1), n -= 1;
    }

    return t;
  };

  var ShapePropertyFactory = function () {
    var s = -999999;

    function t(t, e, r) {
      var i,
          s,
          a,
          n,
          o,
          h,
          l,
          p,
          m,
          f = r.lastIndex,
          c = this.keyframes;
      if (t < c[0].t - this.offsetTime) i = c[0].s[0], a = !0, f = 0;else if (t >= c[c.length - 1].t - this.offsetTime) i = c[c.length - 1].s ? c[c.length - 1].s[0] : c[c.length - 2].e[0], a = !0;else {
        for (var d, u, y = f, g = c.length - 1, v = !0; v && (d = c[y], !((u = c[y + 1]).t - this.offsetTime > t));) {
          y < g - 1 ? y += 1 : v = !1;
        }

        if (f = y, !(a = 1 === d.h)) {
          if (t >= u.t - this.offsetTime) p = 1;else if (t < d.t - this.offsetTime) p = 0;else {
            var b;
            d.__fnct ? b = d.__fnct : (b = BezierFactory.getBezierEasing(d.o.x, d.o.y, d.i.x, d.i.y).get, d.__fnct = b), p = b((t - (d.t - this.offsetTime)) / (u.t - this.offsetTime - (d.t - this.offsetTime)));
          }
          s = u.s ? u.s[0] : d.e[0];
        }

        i = d.s[0];
      }

      for (h = e._length, l = i.i[0].length, r.lastIndex = f, n = 0; n < h; n += 1) {
        for (o = 0; o < l; o += 1) {
          m = a ? i.i[n][o] : i.i[n][o] + (s.i[n][o] - i.i[n][o]) * p, e.i[n][o] = m, m = a ? i.o[n][o] : i.o[n][o] + (s.o[n][o] - i.o[n][o]) * p, e.o[n][o] = m, m = a ? i.v[n][o] : i.v[n][o] + (s.v[n][o] - i.v[n][o]) * p, e.v[n][o] = m;
        }
      }
    }

    function a() {
      this.paths = this.localShapeCollection;
    }

    function e(t) {
      (function (t, e) {
        if (t._length !== e._length || t.c !== e.c) return !1;
        var r,
            i = t._length;

        for (r = 0; r < i; r += 1) {
          if (t.v[r][0] !== e.v[r][0] || t.v[r][1] !== e.v[r][1] || t.o[r][0] !== e.o[r][0] || t.o[r][1] !== e.o[r][1] || t.i[r][0] !== e.i[r][0] || t.i[r][1] !== e.i[r][1]) return !1;
        }

        return !0;
      })(this.v, t) || (this.v = shapePool.clone(t), this.localShapeCollection.releaseShapes(), this.localShapeCollection.addShape(this.v), this._mdf = !0, this.paths = this.localShapeCollection);
    }

    function r() {
      if (this.elem.globalData.frameId !== this.frameId) if (this.effectsSequence.length) {
        if (this.lock) this.setVValue(this.pv);else {
          var t, e;
          this.lock = !0, this._mdf = !1, t = this.kf ? this.pv : this.data.ks ? this.data.ks.k : this.data.pt.k;
          var r = this.effectsSequence.length;

          for (e = 0; e < r; e += 1) {
            t = this.effectsSequence[e](t);
          }

          this.setVValue(t), this.lock = !1, this.frameId = this.elem.globalData.frameId;
        }
      } else this._mdf = !1;
    }

    function n(t, e, r) {
      this.propType = "shape", this.comp = t.comp, this.container = t, this.elem = t, this.data = e, this.k = !1, this.kf = !1, this._mdf = !1;
      var i = 3 === r ? e.pt.k : e.ks.k;
      this.v = shapePool.clone(i), this.pv = shapePool.clone(this.v), this.localShapeCollection = shapeCollectionPool.newShapeCollection(), this.paths = this.localShapeCollection, this.paths.addShape(this.v), this.reset = a, this.effectsSequence = [];
    }

    function i(t) {
      this.effectsSequence.push(t), this.container.addDynamicProperty(this);
    }

    function o(t, e, r) {
      this.propType = "shape", this.comp = t.comp, this.elem = t, this.container = t, this.offsetTime = t.data.st, this.keyframes = 3 === r ? e.pt.k : e.ks.k, this.k = !0, this.kf = !0;
      var i = this.keyframes[0].s[0].i.length;
      this.v = shapePool.newElement(), this.v.setPathData(this.keyframes[0].s[0].c, i), this.pv = shapePool.clone(this.v), this.localShapeCollection = shapeCollectionPool.newShapeCollection(), this.paths = this.localShapeCollection, this.paths.addShape(this.v), this.lastFrame = s, this.reset = a, this._caching = {
        lastFrame: s,
        lastIndex: 0
      }, this.effectsSequence = [function () {
        var t = this.comp.renderedFrame - this.offsetTime,
            e = this.keyframes[0].t - this.offsetTime,
            r = this.keyframes[this.keyframes.length - 1].t - this.offsetTime,
            i = this._caching.lastFrame;
        return i !== s && (i < e && t < e || r < i && r < t) || (this._caching.lastIndex = i < t ? this._caching.lastIndex : 0, this.interpolateShape(t, this.pv, this._caching)), this._caching.lastFrame = t, this.pv;
      }.bind(this)];
    }

    n.prototype.interpolateShape = t, n.prototype.getValue = r, n.prototype.setVValue = e, n.prototype.addEffect = i, o.prototype.getValue = r, o.prototype.interpolateShape = t, o.prototype.setVValue = e, o.prototype.addEffect = i;

    var h = function () {
      var n = roundCorner;

      function t(t, e) {
        this.v = shapePool.newElement(), this.v.setPathData(!0, 4), this.localShapeCollection = shapeCollectionPool.newShapeCollection(), this.paths = this.localShapeCollection, this.localShapeCollection.addShape(this.v), this.d = e.d, this.elem = t, this.comp = t.comp, this.frameId = -1, this.initDynamicPropertyContainer(t), this.p = PropertyFactory.getProp(t, e.p, 1, 0, this), this.s = PropertyFactory.getProp(t, e.s, 1, 0, this), this.dynamicProperties.length ? this.k = !0 : (this.k = !1, this.convertEllToPath());
      }

      return t.prototype = {
        reset: a,
        getValue: function getValue() {
          this.elem.globalData.frameId !== this.frameId && (this.frameId = this.elem.globalData.frameId, this.iterateDynamicProperties(), this._mdf && this.convertEllToPath());
        },
        convertEllToPath: function convertEllToPath() {
          var t = this.p.v[0],
              e = this.p.v[1],
              r = this.s.v[0] / 2,
              i = this.s.v[1] / 2,
              s = 3 !== this.d,
              a = this.v;
          a.v[0][0] = t, a.v[0][1] = e - i, a.v[1][0] = s ? t + r : t - r, a.v[1][1] = e, a.v[2][0] = t, a.v[2][1] = e + i, a.v[3][0] = s ? t - r : t + r, a.v[3][1] = e, a.i[0][0] = s ? t - r * n : t + r * n, a.i[0][1] = e - i, a.i[1][0] = s ? t + r : t - r, a.i[1][1] = e - i * n, a.i[2][0] = s ? t + r * n : t - r * n, a.i[2][1] = e + i, a.i[3][0] = s ? t - r : t + r, a.i[3][1] = e + i * n, a.o[0][0] = s ? t + r * n : t - r * n, a.o[0][1] = e - i, a.o[1][0] = s ? t + r : t - r, a.o[1][1] = e + i * n, a.o[2][0] = s ? t - r * n : t + r * n, a.o[2][1] = e + i, a.o[3][0] = s ? t - r : t + r, a.o[3][1] = e - i * n;
        }
      }, extendPrototype([DynamicPropertyContainer], t), t;
    }(),
        l = function () {
      function t(t, e) {
        this.v = shapePool.newElement(), this.v.setPathData(!0, 0), this.elem = t, this.comp = t.comp, this.data = e, this.frameId = -1, this.d = e.d, this.initDynamicPropertyContainer(t), 1 === e.sy ? (this.ir = PropertyFactory.getProp(t, e.ir, 0, 0, this), this.is = PropertyFactory.getProp(t, e.is, 0, .01, this), this.convertToPath = this.convertStarToPath) : this.convertToPath = this.convertPolygonToPath, this.pt = PropertyFactory.getProp(t, e.pt, 0, 0, this), this.p = PropertyFactory.getProp(t, e.p, 1, 0, this), this.r = PropertyFactory.getProp(t, e.r, 0, degToRads, this), this.or = PropertyFactory.getProp(t, e.or, 0, 0, this), this.os = PropertyFactory.getProp(t, e.os, 0, .01, this), this.localShapeCollection = shapeCollectionPool.newShapeCollection(), this.localShapeCollection.addShape(this.v), this.paths = this.localShapeCollection, this.dynamicProperties.length ? this.k = !0 : (this.k = !1, this.convertToPath());
      }

      return t.prototype = {
        reset: a,
        getValue: function getValue() {
          this.elem.globalData.frameId !== this.frameId && (this.frameId = this.elem.globalData.frameId, this.iterateDynamicProperties(), this._mdf && this.convertToPath());
        },
        convertStarToPath: function convertStarToPath() {
          var t,
              e,
              r,
              i,
              s = 2 * Math.floor(this.pt.v),
              a = 2 * Math.PI / s,
              n = !0,
              o = this.or.v,
              h = this.ir.v,
              l = this.os.v,
              p = this.is.v,
              m = 2 * Math.PI * o / (2 * s),
              f = 2 * Math.PI * h / (2 * s),
              c = -Math.PI / 2;
          c += this.r.v;
          var d = 3 === this.data.d ? -1 : 1;

          for (t = this.v._length = 0; t < s; t += 1) {
            r = n ? l : p, i = n ? m : f;
            var u = (e = n ? o : h) * Math.cos(c),
                y = e * Math.sin(c),
                g = 0 === u && 0 === y ? 0 : y / Math.sqrt(u * u + y * y),
                v = 0 === u && 0 === y ? 0 : -u / Math.sqrt(u * u + y * y);
            u += +this.p.v[0], y += +this.p.v[1], this.v.setTripleAt(u, y, u - g * i * r * d, y - v * i * r * d, u + g * i * r * d, y + v * i * r * d, t, !0), n = !n, c += a * d;
          }
        },
        convertPolygonToPath: function convertPolygonToPath() {
          var t,
              e = Math.floor(this.pt.v),
              r = 2 * Math.PI / e,
              i = this.or.v,
              s = this.os.v,
              a = 2 * Math.PI * i / (4 * e),
              n = .5 * -Math.PI,
              o = 3 === this.data.d ? -1 : 1;

          for (n += this.r.v, t = this.v._length = 0; t < e; t += 1) {
            var h = i * Math.cos(n),
                l = i * Math.sin(n),
                p = 0 === h && 0 === l ? 0 : l / Math.sqrt(h * h + l * l),
                m = 0 === h && 0 === l ? 0 : -h / Math.sqrt(h * h + l * l);
            h += +this.p.v[0], l += +this.p.v[1], this.v.setTripleAt(h, l, h - p * a * s * o, l - m * a * s * o, h + p * a * s * o, l + m * a * s * o, t, !0), n += r * o;
          }

          this.paths.length = 0, this.paths[0] = this.v;
        }
      }, extendPrototype([DynamicPropertyContainer], t), t;
    }(),
        p = function () {
      function t(t, e) {
        this.v = shapePool.newElement(), this.v.c = !0, this.localShapeCollection = shapeCollectionPool.newShapeCollection(), this.localShapeCollection.addShape(this.v), this.paths = this.localShapeCollection, this.elem = t, this.comp = t.comp, this.frameId = -1, this.d = e.d, this.initDynamicPropertyContainer(t), this.p = PropertyFactory.getProp(t, e.p, 1, 0, this), this.s = PropertyFactory.getProp(t, e.s, 1, 0, this), this.r = PropertyFactory.getProp(t, e.r, 0, 0, this), this.dynamicProperties.length ? this.k = !0 : (this.k = !1, this.convertRectToPath());
      }

      return t.prototype = {
        convertRectToPath: function convertRectToPath() {
          var t = this.p.v[0],
              e = this.p.v[1],
              r = this.s.v[0] / 2,
              i = this.s.v[1] / 2,
              s = bmMin(r, i, this.r.v),
              a = s * (1 - roundCorner);
          this.v._length = 0, 2 === this.d || 1 === this.d ? (this.v.setTripleAt(t + r, e - i + s, t + r, e - i + s, t + r, e - i + a, 0, !0), this.v.setTripleAt(t + r, e + i - s, t + r, e + i - a, t + r, e + i - s, 1, !0), 0 !== s ? (this.v.setTripleAt(t + r - s, e + i, t + r - s, e + i, t + r - a, e + i, 2, !0), this.v.setTripleAt(t - r + s, e + i, t - r + a, e + i, t - r + s, e + i, 3, !0), this.v.setTripleAt(t - r, e + i - s, t - r, e + i - s, t - r, e + i - a, 4, !0), this.v.setTripleAt(t - r, e - i + s, t - r, e - i + a, t - r, e - i + s, 5, !0), this.v.setTripleAt(t - r + s, e - i, t - r + s, e - i, t - r + a, e - i, 6, !0), this.v.setTripleAt(t + r - s, e - i, t + r - a, e - i, t + r - s, e - i, 7, !0)) : (this.v.setTripleAt(t - r, e + i, t - r + a, e + i, t - r, e + i, 2), this.v.setTripleAt(t - r, e - i, t - r, e - i + a, t - r, e - i, 3))) : (this.v.setTripleAt(t + r, e - i + s, t + r, e - i + a, t + r, e - i + s, 0, !0), 0 !== s ? (this.v.setTripleAt(t + r - s, e - i, t + r - s, e - i, t + r - a, e - i, 1, !0), this.v.setTripleAt(t - r + s, e - i, t - r + a, e - i, t - r + s, e - i, 2, !0), this.v.setTripleAt(t - r, e - i + s, t - r, e - i + s, t - r, e - i + a, 3, !0), this.v.setTripleAt(t - r, e + i - s, t - r, e + i - a, t - r, e + i - s, 4, !0), this.v.setTripleAt(t - r + s, e + i, t - r + s, e + i, t - r + a, e + i, 5, !0), this.v.setTripleAt(t + r - s, e + i, t + r - a, e + i, t + r - s, e + i, 6, !0), this.v.setTripleAt(t + r, e + i - s, t + r, e + i - s, t + r, e + i - a, 7, !0)) : (this.v.setTripleAt(t - r, e - i, t - r + a, e - i, t - r, e - i, 1, !0), this.v.setTripleAt(t - r, e + i, t - r, e + i - a, t - r, e + i, 2, !0), this.v.setTripleAt(t + r, e + i, t + r - a, e + i, t + r, e + i, 3, !0)));
        },
        getValue: function getValue() {
          this.elem.globalData.frameId !== this.frameId && (this.frameId = this.elem.globalData.frameId, this.iterateDynamicProperties(), this._mdf && this.convertRectToPath());
        },
        reset: a
      }, extendPrototype([DynamicPropertyContainer], t), t;
    }();

    var m = {
      getShapeProp: function getShapeProp(t, e, r) {
        var i;
        return 3 === r || 4 === r ? i = (3 === r ? e.pt : e.ks).k.length ? new o(t, e, r) : new n(t, e, r) : 5 === r ? i = new p(t, e) : 6 === r ? i = new h(t, e) : 7 === r && (i = new l(t, e)), i.k && t.addDynamicProperty(i), i;
      },
      getConstructorFunction: function getConstructorFunction() {
        return n;
      },
      getKeyframedConstructorFunction: function getKeyframedConstructorFunction() {
        return o;
      }
    };
    return m;
  }(),
      ShapeModifiers = (gs = {}, hs = {}, gs.registerModifier = function (t, e) {
    hs[t] || (hs[t] = e);
  }, gs.getModifier = function (t, e, r) {
    return new hs[t](e, r);
  }, gs),
      gs,
      hs;

  function ShapeModifier() {}

  function TrimModifier() {}

  function RoundCornersModifier() {}

  function PuckerAndBloatModifier() {}

  function RepeaterModifier() {}

  function ShapeCollection() {
    this._length = 0, this._maxLength = 4, this.shapes = createSizedArray(this._maxLength);
  }

  function DashProperty(t, e, r, i) {
    var s;
    this.elem = t, this.frameId = -1, this.dataProps = createSizedArray(e.length), this.renderer = r, this.k = !1, this.dashStr = "", this.dashArray = createTypedArray("float32", e.length ? e.length - 1 : 0), this.dashoffset = createTypedArray("float32", 1), this.initDynamicPropertyContainer(i);
    var a,
        n = e.length || 0;

    for (s = 0; s < n; s += 1) {
      a = PropertyFactory.getProp(t, e[s].v, 0, 0, this), this.k = a.k || this.k, this.dataProps[s] = {
        n: e[s].n,
        p: a
      };
    }

    this.k || this.getValue(!0), this._isAnimated = this.k;
  }

  function GradientProperty(t, e, r) {
    this.data = e, this.c = createTypedArray("uint8c", 4 * e.p);
    var i = e.k.k[0].s ? e.k.k[0].s.length - 4 * e.p : e.k.k.length - 4 * e.p;
    this.o = createTypedArray("float32", i), this._cmdf = !1, this._omdf = !1, this._collapsable = this.checkCollapsable(), this._hasOpacity = i, this.initDynamicPropertyContainer(r), this.prop = PropertyFactory.getProp(t, e.k, 1, null, this), this.k = this.prop.k, this.getValue(!0);
  }

  ShapeModifier.prototype.initModifierProperties = function () {}, ShapeModifier.prototype.addShapeToModifier = function () {}, ShapeModifier.prototype.addShape = function (t) {
    if (!this.closed) {
      t.sh.container.addDynamicProperty(t.sh);
      var e = {
        shape: t.sh,
        data: t,
        localShapeCollection: shapeCollectionPool.newShapeCollection()
      };
      this.shapes.push(e), this.addShapeToModifier(e), this._isAnimated && t.setAsAnimated();
    }
  }, ShapeModifier.prototype.init = function (t, e) {
    this.shapes = [], this.elem = t, this.initDynamicPropertyContainer(t), this.initModifierProperties(t, e), this.frameId = initialDefaultFrame, this.closed = !1, this.k = !1, this.dynamicProperties.length ? this.k = !0 : this.getValue(!0);
  }, ShapeModifier.prototype.processKeys = function () {
    this.elem.globalData.frameId !== this.frameId && (this.frameId = this.elem.globalData.frameId, this.iterateDynamicProperties());
  }, extendPrototype([DynamicPropertyContainer], ShapeModifier), extendPrototype([ShapeModifier], TrimModifier), TrimModifier.prototype.initModifierProperties = function (t, e) {
    this.s = PropertyFactory.getProp(t, e.s, 0, .01, this), this.e = PropertyFactory.getProp(t, e.e, 0, .01, this), this.o = PropertyFactory.getProp(t, e.o, 0, 0, this), this.sValue = 0, this.eValue = 0, this.getValue = this.processKeys, this.m = e.m, this._isAnimated = !!this.s.effectsSequence.length || !!this.e.effectsSequence.length || !!this.o.effectsSequence.length;
  }, TrimModifier.prototype.addShapeToModifier = function (t) {
    t.pathsData = [];
  }, TrimModifier.prototype.calculateShapeEdges = function (t, e, r, i, s) {
    var a = [];
    e <= 1 ? a.push({
      s: t,
      e: e
    }) : 1 <= t ? a.push({
      s: t - 1,
      e: e - 1
    }) : (a.push({
      s: t,
      e: 1
    }), a.push({
      s: 0,
      e: e - 1
    }));
    var n,
        o,
        h = [],
        l = a.length;

    for (n = 0; n < l; n += 1) {
      var p, m;
      if (!((o = a[n]).e * s < i || o.s * s > i + r)) p = o.s * s <= i ? 0 : (o.s * s - i) / r, m = o.e * s >= i + r ? 1 : (o.e * s - i) / r, h.push([p, m]);
    }

    return h.length || h.push([0, 0]), h;
  }, TrimModifier.prototype.releasePathsData = function (t) {
    var e,
        r = t.length;

    for (e = 0; e < r; e += 1) {
      segmentsLengthPool.release(t[e]);
    }

    return t.length = 0, t;
  }, TrimModifier.prototype.processShapes = function (t) {
    var e, r, i, s;

    if (this._mdf || t) {
      var a = this.o.v % 360 / 360;

      if (a < 0 && (a += 1), e = 1 < this.s.v ? 1 + a : this.s.v < 0 ? 0 + a : this.s.v + a, (r = 1 < this.e.v ? 1 + a : this.e.v < 0 ? 0 + a : this.e.v + a) < e) {
        var n = e;
        e = r, r = n;
      }

      e = 1e-4 * Math.round(1e4 * e), r = 1e-4 * Math.round(1e4 * r), this.sValue = e, this.eValue = r;
    } else e = this.sValue, r = this.eValue;

    var o,
        h,
        l,
        p,
        m,
        f = this.shapes.length,
        c = 0;
    if (r === e) for (s = 0; s < f; s += 1) {
      this.shapes[s].localShapeCollection.releaseShapes(), this.shapes[s].shape._mdf = !0, this.shapes[s].shape.paths = this.shapes[s].localShapeCollection, this._mdf && (this.shapes[s].pathsData.length = 0);
    } else if (1 === r && 0 === e || 0 === r && 1 === e) {
      if (this._mdf) for (s = 0; s < f; s += 1) {
        this.shapes[s].pathsData.length = 0, this.shapes[s].shape._mdf = !0;
      }
    } else {
      var d,
          u,
          y = [];

      for (s = 0; s < f; s += 1) {
        if ((d = this.shapes[s]).shape._mdf || this._mdf || t || 2 === this.m) {
          if (h = (i = d.shape.paths)._length, m = 0, !d.shape._mdf && d.pathsData.length) m = d.totalShapeLength;else {
            for (l = this.releasePathsData(d.pathsData), o = 0; o < h; o += 1) {
              p = bez.getSegmentsLength(i.shapes[o]), l.push(p), m += p.totalLength;
            }

            d.totalShapeLength = m, d.pathsData = l;
          }
          c += m, d.shape._mdf = !0;
        } else d.shape.paths = d.localShapeCollection;
      }

      var g,
          v = e,
          b = r,
          P = 0;

      for (s = f - 1; 0 <= s; s -= 1) {
        if ((d = this.shapes[s]).shape._mdf) {
          for ((u = d.localShapeCollection).releaseShapes(), 2 === this.m && 1 < f ? (g = this.calculateShapeEdges(e, r, d.totalShapeLength, P, c), P += d.totalShapeLength) : g = [[v, b]], h = g.length, o = 0; o < h; o += 1) {
            v = g[o][0], b = g[o][1], y.length = 0, b <= 1 ? y.push({
              s: d.totalShapeLength * v,
              e: d.totalShapeLength * b
            }) : 1 <= v ? y.push({
              s: d.totalShapeLength * (v - 1),
              e: d.totalShapeLength * (b - 1)
            }) : (y.push({
              s: d.totalShapeLength * v,
              e: d.totalShapeLength
            }), y.push({
              s: 0,
              e: d.totalShapeLength * (b - 1)
            }));
            var E = this.addShapes(d, y[0]);

            if (y[0].s !== y[0].e) {
              if (1 < y.length) if (d.shape.paths.shapes[d.shape.paths._length - 1].c) {
                var x = E.pop();
                this.addPaths(E, u), E = this.addShapes(d, y[1], x);
              } else this.addPaths(E, u), E = this.addShapes(d, y[1]);
              this.addPaths(E, u);
            }
          }

          d.shape.paths = u;
        }
      }
    }
  }, TrimModifier.prototype.addPaths = function (t, e) {
    var r,
        i = t.length;

    for (r = 0; r < i; r += 1) {
      e.addShape(t[r]);
    }
  }, TrimModifier.prototype.addSegment = function (t, e, r, i, s, a, n) {
    s.setXYAt(e[0], e[1], "o", a), s.setXYAt(r[0], r[1], "i", a + 1), n && s.setXYAt(t[0], t[1], "v", a), s.setXYAt(i[0], i[1], "v", a + 1);
  }, TrimModifier.prototype.addSegmentFromArray = function (t, e, r, i) {
    e.setXYAt(t[1], t[5], "o", r), e.setXYAt(t[2], t[6], "i", r + 1), i && e.setXYAt(t[0], t[4], "v", r), e.setXYAt(t[3], t[7], "v", r + 1);
  }, TrimModifier.prototype.addShapes = function (t, e, r) {
    var i,
        s,
        a,
        n,
        o,
        h,
        l,
        p,
        m = t.pathsData,
        f = t.shape.paths.shapes,
        c = t.shape.paths._length,
        d = 0,
        u = [],
        y = !0;

    for (p = r ? (o = r._length, r._length) : (r = shapePool.newElement(), o = 0), u.push(r), i = 0; i < c; i += 1) {
      for (h = m[i].lengths, r.c = f[i].c, a = f[i].c ? h.length : h.length + 1, s = 1; s < a; s += 1) {
        if (d + (n = h[s - 1]).addedLength < e.s) d += n.addedLength, r.c = !1;else {
          if (d > e.e) {
            r.c = !1;
            break;
          }

          e.s <= d && e.e >= d + n.addedLength ? (this.addSegment(f[i].v[s - 1], f[i].o[s - 1], f[i].i[s], f[i].v[s], r, o, y), y = !1) : (l = bez.getNewSegment(f[i].v[s - 1], f[i].v[s], f[i].o[s - 1], f[i].i[s], (e.s - d) / n.addedLength, (e.e - d) / n.addedLength, h[s - 1]), this.addSegmentFromArray(l, r, o, y), y = !1, r.c = !1), d += n.addedLength, o += 1;
        }
      }

      if (f[i].c && h.length) {
        if (n = h[s - 1], d <= e.e) {
          var g = h[s - 1].addedLength;
          e.s <= d && e.e >= d + g ? (this.addSegment(f[i].v[s - 1], f[i].o[s - 1], f[i].i[0], f[i].v[0], r, o, y), y = !1) : (l = bez.getNewSegment(f[i].v[s - 1], f[i].v[0], f[i].o[s - 1], f[i].i[0], (e.s - d) / g, (e.e - d) / g, h[s - 1]), this.addSegmentFromArray(l, r, o, y), y = !1, r.c = !1);
        } else r.c = !1;

        d += n.addedLength, o += 1;
      }

      if (r._length && (r.setXYAt(r.v[p][0], r.v[p][1], "i", p), r.setXYAt(r.v[r._length - 1][0], r.v[r._length - 1][1], "o", r._length - 1)), d > e.e) break;
      i < c - 1 && (r = shapePool.newElement(), y = !0, u.push(r), o = 0);
    }

    return u;
  }, ShapeModifiers.registerModifier("tm", TrimModifier), extendPrototype([ShapeModifier], RoundCornersModifier), RoundCornersModifier.prototype.initModifierProperties = function (t, e) {
    this.getValue = this.processKeys, this.rd = PropertyFactory.getProp(t, e.r, 0, null, this), this._isAnimated = !!this.rd.effectsSequence.length;
  }, RoundCornersModifier.prototype.processPath = function (t, e) {
    var r,
        i = shapePool.newElement();
    i.c = t.c;
    var s,
        a,
        n,
        o,
        h,
        l,
        p,
        m,
        f,
        c,
        d,
        u,
        y = t._length,
        g = 0;

    for (r = 0; r < y; r += 1) {
      s = t.v[r], n = t.o[r], a = t.i[r], s[0] === n[0] && s[1] === n[1] && s[0] === a[0] && s[1] === a[1] ? 0 !== r && r !== y - 1 || t.c ? (o = 0 === r ? t.v[y - 1] : t.v[r - 1], l = (h = Math.sqrt(Math.pow(s[0] - o[0], 2) + Math.pow(s[1] - o[1], 2))) ? Math.min(h / 2, e) / h : 0, p = d = s[0] + (o[0] - s[0]) * l, m = u = s[1] - (s[1] - o[1]) * l, f = p - (p - s[0]) * roundCorner, c = m - (m - s[1]) * roundCorner, i.setTripleAt(p, m, f, c, d, u, g), g += 1, o = r === y - 1 ? t.v[0] : t.v[r + 1], l = (h = Math.sqrt(Math.pow(s[0] - o[0], 2) + Math.pow(s[1] - o[1], 2))) ? Math.min(h / 2, e) / h : 0, p = f = s[0] + (o[0] - s[0]) * l, m = c = s[1] + (o[1] - s[1]) * l, d = p - (p - s[0]) * roundCorner, u = m - (m - s[1]) * roundCorner, i.setTripleAt(p, m, f, c, d, u, g)) : i.setTripleAt(s[0], s[1], n[0], n[1], a[0], a[1], g) : i.setTripleAt(t.v[r][0], t.v[r][1], t.o[r][0], t.o[r][1], t.i[r][0], t.i[r][1], g), g += 1;
    }

    return i;
  }, RoundCornersModifier.prototype.processShapes = function (t) {
    var e,
        r,
        i,
        s,
        a,
        n,
        o = this.shapes.length,
        h = this.rd.v;
    if (0 !== h) for (r = 0; r < o; r += 1) {
      if (n = (a = this.shapes[r]).localShapeCollection, a.shape._mdf || this._mdf || t) for (n.releaseShapes(), a.shape._mdf = !0, e = a.shape.paths.shapes, s = a.shape.paths._length, i = 0; i < s; i += 1) {
        n.addShape(this.processPath(e[i], h));
      }
      a.shape.paths = a.localShapeCollection;
    }
    this.dynamicProperties.length || (this._mdf = !1);
  }, ShapeModifiers.registerModifier("rd", RoundCornersModifier), extendPrototype([ShapeModifier], PuckerAndBloatModifier), PuckerAndBloatModifier.prototype.initModifierProperties = function (t, e) {
    this.getValue = this.processKeys, this.amount = PropertyFactory.getProp(t, e.a, 0, null, this), this._isAnimated = !!this.amount.effectsSequence.length;
  }, PuckerAndBloatModifier.prototype.processPath = function (t, e) {
    var r = e / 100,
        i = [0, 0],
        s = t._length,
        a = 0;

    for (a = 0; a < s; a += 1) {
      i[0] += t.v[a][0], i[1] += t.v[a][1];
    }

    i[0] /= s, i[1] /= s;
    var n,
        o,
        h,
        l,
        p,
        m,
        f = shapePool.newElement();

    for (f.c = t.c, a = 0; a < s; a += 1) {
      n = t.v[a][0] + (i[0] - t.v[a][0]) * r, o = t.v[a][1] + (i[1] - t.v[a][1]) * r, h = t.o[a][0] + (i[0] - t.o[a][0]) * -r, l = t.o[a][1] + (i[1] - t.o[a][1]) * -r, p = t.i[a][0] + (i[0] - t.i[a][0]) * -r, m = t.i[a][1] + (i[1] - t.i[a][1]) * -r, f.setTripleAt(n, o, h, l, p, m, a);
    }

    return f;
  }, PuckerAndBloatModifier.prototype.processShapes = function (t) {
    var e,
        r,
        i,
        s,
        a,
        n,
        o = this.shapes.length,
        h = this.amount.v;
    if (0 !== h) for (r = 0; r < o; r += 1) {
      if (n = (a = this.shapes[r]).localShapeCollection, a.shape._mdf || this._mdf || t) for (n.releaseShapes(), a.shape._mdf = !0, e = a.shape.paths.shapes, s = a.shape.paths._length, i = 0; i < s; i += 1) {
        n.addShape(this.processPath(e[i], h));
      }
      a.shape.paths = a.localShapeCollection;
    }
    this.dynamicProperties.length || (this._mdf = !1);
  }, ShapeModifiers.registerModifier("pb", PuckerAndBloatModifier), extendPrototype([ShapeModifier], RepeaterModifier), RepeaterModifier.prototype.initModifierProperties = function (t, e) {
    this.getValue = this.processKeys, this.c = PropertyFactory.getProp(t, e.c, 0, null, this), this.o = PropertyFactory.getProp(t, e.o, 0, null, this), this.tr = TransformPropertyFactory.getTransformProperty(t, e.tr, this), this.so = PropertyFactory.getProp(t, e.tr.so, 0, .01, this), this.eo = PropertyFactory.getProp(t, e.tr.eo, 0, .01, this), this.data = e, this.dynamicProperties.length || this.getValue(!0), this._isAnimated = !!this.dynamicProperties.length, this.pMatrix = new Matrix(), this.rMatrix = new Matrix(), this.sMatrix = new Matrix(), this.tMatrix = new Matrix(), this.matrix = new Matrix();
  }, RepeaterModifier.prototype.applyTransforms = function (t, e, r, i, s, a) {
    var n = a ? -1 : 1,
        o = i.s.v[0] + (1 - i.s.v[0]) * (1 - s),
        h = i.s.v[1] + (1 - i.s.v[1]) * (1 - s);
    t.translate(i.p.v[0] * n * s, i.p.v[1] * n * s, i.p.v[2]), e.translate(-i.a.v[0], -i.a.v[1], i.a.v[2]), e.rotate(-i.r.v * n * s), e.translate(i.a.v[0], i.a.v[1], i.a.v[2]), r.translate(-i.a.v[0], -i.a.v[1], i.a.v[2]), r.scale(a ? 1 / o : o, a ? 1 / h : h), r.translate(i.a.v[0], i.a.v[1], i.a.v[2]);
  }, RepeaterModifier.prototype.init = function (t, e, r, i) {
    for (this.elem = t, this.arr = e, this.pos = r, this.elemsData = i, this._currentCopies = 0, this._elements = [], this._groups = [], this.frameId = -1, this.initDynamicPropertyContainer(t), this.initModifierProperties(t, e[r]); 0 < r;) {
      r -= 1, this._elements.unshift(e[r]);
    }

    this.dynamicProperties.length ? this.k = !0 : this.getValue(!0);
  }, RepeaterModifier.prototype.resetElements = function (t) {
    var e,
        r = t.length;

    for (e = 0; e < r; e += 1) {
      t[e]._processed = !1, "gr" === t[e].ty && this.resetElements(t[e].it);
    }
  }, RepeaterModifier.prototype.cloneElements = function (t) {
    var e = JSON.parse(JSON.stringify(t));
    return this.resetElements(e), e;
  }, RepeaterModifier.prototype.changeGroupRender = function (t, e) {
    var r,
        i = t.length;

    for (r = 0; r < i; r += 1) {
      t[r]._render = e, "gr" === t[r].ty && this.changeGroupRender(t[r].it, e);
    }
  }, RepeaterModifier.prototype.processShapes = function (t) {
    var e,
        r,
        i,
        s,
        a,
        n = !1;

    if (this._mdf || t) {
      var o,
          h = Math.ceil(this.c.v);

      if (this._groups.length < h) {
        for (; this._groups.length < h;) {
          var l = {
            it: this.cloneElements(this._elements),
            ty: "gr"
          };
          l.it.push({
            a: {
              a: 0,
              ix: 1,
              k: [0, 0]
            },
            nm: "Transform",
            o: {
              a: 0,
              ix: 7,
              k: 100
            },
            p: {
              a: 0,
              ix: 2,
              k: [0, 0]
            },
            r: {
              a: 1,
              ix: 6,
              k: [{
                s: 0,
                e: 0,
                t: 0
              }, {
                s: 0,
                e: 0,
                t: 1
              }]
            },
            s: {
              a: 0,
              ix: 3,
              k: [100, 100]
            },
            sa: {
              a: 0,
              ix: 5,
              k: 0
            },
            sk: {
              a: 0,
              ix: 4,
              k: 0
            },
            ty: "tr"
          }), this.arr.splice(0, 0, l), this._groups.splice(0, 0, l), this._currentCopies += 1;
        }

        this.elem.reloadShapes(), n = !0;
      }

      for (i = a = 0; i <= this._groups.length - 1; i += 1) {
        if (o = a < h, this._groups[i]._render = o, this.changeGroupRender(this._groups[i].it, o), !o) {
          var p = this.elemsData[i].it,
              m = p[p.length - 1];
          0 !== m.transform.op.v ? (m.transform.op._mdf = !0, m.transform.op.v = 0) : m.transform.op._mdf = !1;
        }

        a += 1;
      }

      this._currentCopies = h;
      var f = this.o.v,
          c = f % 1,
          d = 0 < f ? Math.floor(f) : Math.ceil(f),
          u = this.pMatrix.props,
          y = this.rMatrix.props,
          g = this.sMatrix.props;
      this.pMatrix.reset(), this.rMatrix.reset(), this.sMatrix.reset(), this.tMatrix.reset(), this.matrix.reset();
      var v,
          b,
          P = 0;

      if (0 < f) {
        for (; P < d;) {
          this.applyTransforms(this.pMatrix, this.rMatrix, this.sMatrix, this.tr, 1, !1), P += 1;
        }

        c && (this.applyTransforms(this.pMatrix, this.rMatrix, this.sMatrix, this.tr, c, !1), P += c);
      } else if (f < 0) {
        for (; d < P;) {
          this.applyTransforms(this.pMatrix, this.rMatrix, this.sMatrix, this.tr, 1, !0), P -= 1;
        }

        c && (this.applyTransforms(this.pMatrix, this.rMatrix, this.sMatrix, this.tr, -c, !0), P -= c);
      }

      for (i = 1 === this.data.m ? 0 : this._currentCopies - 1, s = 1 === this.data.m ? 1 : -1, a = this._currentCopies; a;) {
        if (b = (r = (e = this.elemsData[i].it)[e.length - 1].transform.mProps.v.props).length, e[e.length - 1].transform.mProps._mdf = !0, e[e.length - 1].transform.op._mdf = !0, e[e.length - 1].transform.op.v = 1 === this._currentCopies ? this.so.v : this.so.v + (this.eo.v - this.so.v) * (i / (this._currentCopies - 1)), 0 !== P) {
          for ((0 !== i && 1 === s || i !== this._currentCopies - 1 && -1 === s) && this.applyTransforms(this.pMatrix, this.rMatrix, this.sMatrix, this.tr, 1, !1), this.matrix.transform(y[0], y[1], y[2], y[3], y[4], y[5], y[6], y[7], y[8], y[9], y[10], y[11], y[12], y[13], y[14], y[15]), this.matrix.transform(g[0], g[1], g[2], g[3], g[4], g[5], g[6], g[7], g[8], g[9], g[10], g[11], g[12], g[13], g[14], g[15]), this.matrix.transform(u[0], u[1], u[2], u[3], u[4], u[5], u[6], u[7], u[8], u[9], u[10], u[11], u[12], u[13], u[14], u[15]), v = 0; v < b; v += 1) {
            r[v] = this.matrix.props[v];
          }

          this.matrix.reset();
        } else for (this.matrix.reset(), v = 0; v < b; v += 1) {
          r[v] = this.matrix.props[v];
        }

        P += 1, a -= 1, i += s;
      }
    } else for (a = this._currentCopies, i = 0, s = 1; a;) {
      r = (e = this.elemsData[i].it)[e.length - 1].transform.mProps.v.props, e[e.length - 1].transform.mProps._mdf = !1, e[e.length - 1].transform.op._mdf = !1, a -= 1, i += s;
    }

    return n;
  }, RepeaterModifier.prototype.addShape = function () {}, ShapeModifiers.registerModifier("rp", RepeaterModifier), ShapeCollection.prototype.addShape = function (t) {
    this._length === this._maxLength && (this.shapes = this.shapes.concat(createSizedArray(this._maxLength)), this._maxLength *= 2), this.shapes[this._length] = t, this._length += 1;
  }, ShapeCollection.prototype.releaseShapes = function () {
    var t;

    for (t = 0; t < this._length; t += 1) {
      shapePool.release(this.shapes[t]);
    }

    this._length = 0;
  }, DashProperty.prototype.getValue = function (t) {
    if ((this.elem.globalData.frameId !== this.frameId || t) && (this.frameId = this.elem.globalData.frameId, this.iterateDynamicProperties(), this._mdf = this._mdf || t, this._mdf)) {
      var e = 0,
          r = this.dataProps.length;

      for ("svg" === this.renderer && (this.dashStr = ""), e = 0; e < r; e += 1) {
        "o" !== this.dataProps[e].n ? "svg" === this.renderer ? this.dashStr += " " + this.dataProps[e].p.v : this.dashArray[e] = this.dataProps[e].p.v : this.dashoffset[0] = this.dataProps[e].p.v;
      }
    }
  }, extendPrototype([DynamicPropertyContainer], DashProperty), GradientProperty.prototype.comparePoints = function (t, e) {
    for (var r = 0, i = this.o.length / 2; r < i;) {
      if (.01 < Math.abs(t[4 * r] - t[4 * e + 2 * r])) return !1;
      r += 1;
    }

    return !0;
  }, GradientProperty.prototype.checkCollapsable = function () {
    if (this.o.length / 2 != this.c.length / 4) return !1;
    if (this.data.k.k[0].s) for (var t = 0, e = this.data.k.k.length; t < e;) {
      if (!this.comparePoints(this.data.k.k[t].s, this.data.p)) return !1;
      t += 1;
    } else if (!this.comparePoints(this.data.k.k, this.data.p)) return !1;
    return !0;
  }, GradientProperty.prototype.getValue = function (t) {
    if (this.prop.getValue(), this._mdf = !1, this._cmdf = !1, this._omdf = !1, this.prop._mdf || t) {
      var e,
          r,
          i,
          s = 4 * this.data.p;

      for (e = 0; e < s; e += 1) {
        r = e % 4 == 0 ? 100 : 255, i = Math.round(this.prop.v[e] * r), this.c[e] !== i && (this.c[e] = i, this._cmdf = !t);
      }

      if (this.o.length) for (s = this.prop.v.length, e = 4 * this.data.p; e < s; e += 1) {
        r = e % 2 == 0 ? 100 : 1, i = e % 2 == 0 ? Math.round(100 * this.prop.v[e]) : this.prop.v[e], this.o[e - 4 * this.data.p] !== i && (this.o[e - 4 * this.data.p] = i, this._omdf = !t);
      }
      this._mdf = !t;
    }
  }, extendPrototype([DynamicPropertyContainer], GradientProperty);

  var buildShapeString = function buildShapeString(t, e, r, i) {
    if (0 === e) return "";
    var s,
        a = t.o,
        n = t.i,
        o = t.v,
        h = " M" + i.applyToPointStringified(o[0][0], o[0][1]);

    for (s = 1; s < e; s += 1) {
      h += " C" + i.applyToPointStringified(a[s - 1][0], a[s - 1][1]) + " " + i.applyToPointStringified(n[s][0], n[s][1]) + " " + i.applyToPointStringified(o[s][0], o[s][1]);
    }

    return r && e && (h += " C" + i.applyToPointStringified(a[s - 1][0], a[s - 1][1]) + " " + i.applyToPointStringified(n[0][0], n[0][1]) + " " + i.applyToPointStringified(o[0][0], o[0][1]), h += "z"), h;
  },
      audioControllerFactory = function () {
    function t(t) {
      this.audios = [], this.audioFactory = t, this._volume = 1, this._isMuted = !1;
    }

    return t.prototype = {
      addAudio: function addAudio(t) {
        this.audios.push(t);
      },
      pause: function pause() {
        var t,
            e = this.audios.length;

        for (t = 0; t < e; t += 1) {
          this.audios[t].pause();
        }
      },
      resume: function resume() {
        var t,
            e = this.audios.length;

        for (t = 0; t < e; t += 1) {
          this.audios[t].resume();
        }
      },
      setRate: function setRate(t) {
        var e,
            r = this.audios.length;

        for (e = 0; e < r; e += 1) {
          this.audios[e].setRate(t);
        }
      },
      createAudio: function createAudio(t) {
        return this.audioFactory ? this.audioFactory(t) : Howl ? new Howl({
          src: [t]
        }) : {
          isPlaying: !1,
          play: function play() {
            this.isPlaying = !0;
          },
          seek: function seek() {
            this.isPlaying = !1;
          },
          playing: function playing() {},
          rate: function rate() {},
          setVolume: function setVolume() {}
        };
      },
      setAudioFactory: function setAudioFactory(t) {
        this.audioFactory = t;
      },
      setVolume: function setVolume(t) {
        this._volume = t, this._updateVolume();
      },
      mute: function mute() {
        this._isMuted = !0, this._updateVolume();
      },
      unmute: function unmute() {
        this._isMuted = !1, this._updateVolume();
      },
      getVolume: function getVolume() {
        return this._volume;
      },
      _updateVolume: function _updateVolume() {
        var t,
            e = this.audios.length;

        for (t = 0; t < e; t += 1) {
          this.audios[t].volume(this._volume * (this._isMuted ? 0 : 1));
        }
      }
    }, function () {
      return new t();
    };
  }(),
      ImagePreloader = function () {
    var s = function () {
      var t = createTag("canvas");
      t.width = 1, t.height = 1;
      var e = t.getContext("2d");
      return e.fillStyle = "rgba(0,0,0,0)", e.fillRect(0, 0, 1, 1), t;
    }();

    function t() {
      this.loadedAssets += 1, this.loadedAssets === this.totalImages && this.loadedFootagesCount === this.totalFootages && this.imagesLoadedCb && this.imagesLoadedCb(null);
    }

    function e() {
      this.loadedFootagesCount += 1, this.loadedAssets === this.totalImages && this.loadedFootagesCount === this.totalFootages && this.imagesLoadedCb && this.imagesLoadedCb(null);
    }

    function a(t, e, r) {
      var i = "";
      if (t.e) i = t.p;else if (e) {
        var s = t.p;
        -1 !== s.indexOf("images/") && (s = s.split("/")[1]), i = e + s;
      } else i = r, i += t.u ? t.u : "", i += t.p;
      return i;
    }

    function r() {
      this._imageLoaded = t.bind(this), this._footageLoaded = e.bind(this), this.testImageLoaded = function (t) {
        var e = 0,
            r = setInterval(function () {
          (t.getBBox().width || 500 < e) && (this._imageLoaded(), clearInterval(r)), e += 1;
        }.bind(this), 50);
      }.bind(this), this.createFootageData = function (t) {
        var e = {
          assetData: t
        },
            r = a(t, this.assetsPath, this.path);
        return assetLoader.load(r, function (t) {
          e.img = t, this._footageLoaded();
        }.bind(this), function () {
          e.img = {}, this._footageLoaded();
        }.bind(this)), e;
      }.bind(this), this.assetsPath = "", this.path = "", this.totalImages = 0, this.totalFootages = 0, this.loadedAssets = 0, this.loadedFootagesCount = 0, this.imagesLoadedCb = null, this.images = [];
    }

    return r.prototype = {
      loadAssets: function loadAssets(t, e) {
        var r;
        this.imagesLoadedCb = e;
        var i = t.length;

        for (r = 0; r < i; r += 1) {
          t[r].layers || (t[r].t && "seq" !== t[r].t ? 3 === t[r].t && (this.totalFootages += 1, this.images.push(this.createFootageData(t[r]))) : (this.totalImages += 1, this.images.push(this._createImageData(t[r]))));
        }
      },
      setAssetsPath: function setAssetsPath(t) {
        this.assetsPath = t || "";
      },
      setPath: function setPath(t) {
        this.path = t || "";
      },
      loadedImages: function loadedImages() {
        return this.totalImages === this.loadedAssets;
      },
      loadedFootages: function loadedFootages() {
        return this.totalFootages === this.loadedFootagesCount;
      },
      destroy: function destroy() {
        this.imagesLoadedCb = null, this.images.length = 0;
      },
      getAsset: function getAsset(t) {
        for (var e = 0, r = this.images.length; e < r;) {
          if (this.images[e].assetData === t) return this.images[e].img;
          e += 1;
        }

        return null;
      },
      createImgData: function createImgData(t) {
        var e = a(t, this.assetsPath, this.path),
            r = createTag("img");
        r.crossOrigin = "anonymous", r.addEventListener("load", this._imageLoaded, !1), r.addEventListener("error", function () {
          i.img = s, this._imageLoaded();
        }.bind(this), !1), r.src = e;
        var i = {
          img: r,
          assetData: t
        };
        return i;
      },
      createImageData: function createImageData(t) {
        var e = a(t, this.assetsPath, this.path),
            r = createNS("image");
        isSafari ? this.testImageLoaded(r) : r.addEventListener("load", this._imageLoaded, !1), r.addEventListener("error", function () {
          i.img = s, this._imageLoaded();
        }.bind(this), !1), r.setAttributeNS("http://www.w3.org/1999/xlink", "href", e), this._elementHelper.append ? this._elementHelper.append(r) : this._elementHelper.appendChild(r);
        var i = {
          img: r,
          assetData: t
        };
        return i;
      },
      imageLoaded: t,
      footageLoaded: e,
      setCacheType: function setCacheType(t, e) {
        this._createImageData = "svg" === t ? (this._elementHelper = e, this.createImageData.bind(this)) : this.createImgData.bind(this);
      }
    }, r;
  }(),
      featureSupport = (zx = {
    maskType: !0
  }, (/MSIE 10/i.test(navigator.userAgent) || /MSIE 9/i.test(navigator.userAgent) || /rv:11.0/i.test(navigator.userAgent) || /Edge\/\d./i.test(navigator.userAgent)) && (zx.maskType = !1), zx),
      zx,
      filtersFactory = (Ax = {}, Ax.createFilter = function (t, e) {
    var r = createNS("filter");
    return r.setAttribute("id", t), !0 !== e && (r.setAttribute("filterUnits", "objectBoundingBox"), r.setAttribute("x", "0%"), r.setAttribute("y", "0%"), r.setAttribute("width", "100%"), r.setAttribute("height", "100%")), r;
  }, Ax.createAlphaToLuminanceFilter = function () {
    var t = createNS("feColorMatrix");
    return t.setAttribute("type", "matrix"), t.setAttribute("color-interpolation-filters", "sRGB"), t.setAttribute("values", "0 0 0 1 0  0 0 0 1 0  0 0 0 1 0  0 0 0 1 1"), t;
  }, Ax),
      Ax,
      assetLoader = function () {
    function a(t) {
      return t.response && "object" == _typeof(t.response) ? t.response : t.response && "string" == typeof t.response ? JSON.parse(t.response) : t.responseText ? JSON.parse(t.responseText) : null;
    }

    return {
      load: function load(t, e, r) {
        var i,
            s = new XMLHttpRequest();

        try {
          s.responseType = "json";
        } catch (t) {}

        s.onreadystatechange = function () {
          if (4 === s.readyState) if (200 === s.status) i = a(s), e(i);else try {
            i = a(s), e(i);
          } catch (t) {
            r && r(t);
          }
        }, s.open("GET", t, !0), s.send();
      }
    };
  }();

  function TextAnimatorProperty(t, e, r) {
    this._isFirstFrame = !0, this._hasMaskedPath = !1, this._frameId = -1, this._textData = t, this._renderType = e, this._elem = r, this._animatorsData = createSizedArray(this._textData.a.length), this._pathData = {}, this._moreOptions = {
      alignment: {}
    }, this.renderedLetters = [], this.lettersChangedFlag = !1, this.initDynamicPropertyContainer(r);
  }

  function TextAnimatorDataProperty(t, e, r) {
    var i = {
      propType: !1
    },
        s = PropertyFactory.getProp,
        a = e.a;
    this.a = {
      r: a.r ? s(t, a.r, 0, degToRads, r) : i,
      rx: a.rx ? s(t, a.rx, 0, degToRads, r) : i,
      ry: a.ry ? s(t, a.ry, 0, degToRads, r) : i,
      sk: a.sk ? s(t, a.sk, 0, degToRads, r) : i,
      sa: a.sa ? s(t, a.sa, 0, degToRads, r) : i,
      s: a.s ? s(t, a.s, 1, .01, r) : i,
      a: a.a ? s(t, a.a, 1, 0, r) : i,
      o: a.o ? s(t, a.o, 0, .01, r) : i,
      p: a.p ? s(t, a.p, 1, 0, r) : i,
      sw: a.sw ? s(t, a.sw, 0, 0, r) : i,
      sc: a.sc ? s(t, a.sc, 1, 0, r) : i,
      fc: a.fc ? s(t, a.fc, 1, 0, r) : i,
      fh: a.fh ? s(t, a.fh, 0, 0, r) : i,
      fs: a.fs ? s(t, a.fs, 0, .01, r) : i,
      fb: a.fb ? s(t, a.fb, 0, .01, r) : i,
      t: a.t ? s(t, a.t, 0, 0, r) : i
    }, this.s = TextSelectorProp.getTextSelectorProp(t, e.s, r), this.s.t = e.s.t;
  }

  function LetterProps(t, e, r, i, s, a) {
    this.o = t, this.sw = e, this.sc = r, this.fc = i, this.m = s, this.p = a, this._mdf = {
      o: !0,
      sw: !!e,
      sc: !!r,
      fc: !!i,
      m: !0,
      p: !0
    };
  }

  function TextProperty(t, e) {
    this._frameId = initialDefaultFrame, this.pv = "", this.v = "", this.kf = !1, this._isFirstFrame = !0, this._mdf = !1, this.data = e, this.elem = t, this.comp = this.elem.comp, this.keysIndex = 0, this.canResize = !1, this.minimumFontSize = 1, this.effectsSequence = [], this.currentData = {
      ascent: 0,
      boxWidth: this.defaultBoxWidth,
      f: "",
      fStyle: "",
      fWeight: "",
      fc: "",
      j: "",
      justifyOffset: "",
      l: [],
      lh: 0,
      lineWidths: [],
      ls: "",
      of: "",
      s: "",
      sc: "",
      sw: 0,
      t: 0,
      tr: 0,
      sz: 0,
      ps: null,
      fillColorAnim: !1,
      strokeColorAnim: !1,
      strokeWidthAnim: !1,
      yOffset: 0,
      finalSize: 0,
      finalText: [],
      finalLineHeight: 0,
      __complete: !1
    }, this.copyData(this.currentData, this.data.d.k[0].s), this.searchProperty() || this.completeTextData(this.currentData);
  }

  TextAnimatorProperty.prototype.searchProperties = function () {
    var t,
        e,
        r = this._textData.a.length,
        i = PropertyFactory.getProp;

    for (t = 0; t < r; t += 1) {
      e = this._textData.a[t], this._animatorsData[t] = new TextAnimatorDataProperty(this._elem, e, this);
    }

    this._textData.p && "m" in this._textData.p ? (this._pathData = {
      f: i(this._elem, this._textData.p.f, 0, 0, this),
      l: i(this._elem, this._textData.p.l, 0, 0, this),
      r: this._textData.p.r,
      m: this._elem.maskManager.getMaskProperty(this._textData.p.m)
    }, this._hasMaskedPath = !0) : this._hasMaskedPath = !1, this._moreOptions.alignment = i(this._elem, this._textData.m.a, 1, 0, this);
  }, TextAnimatorProperty.prototype.getMeasures = function (t, e) {
    if (this.lettersChangedFlag = e, this._mdf || this._isFirstFrame || e || this._hasMaskedPath && this._pathData.m._mdf) {
      this._isFirstFrame = !1;
      var r,
          i,
          s,
          a,
          n,
          o,
          h,
          l,
          p,
          m,
          f,
          c,
          d,
          u,
          y,
          g,
          v,
          b,
          P,
          E = this._moreOptions.alignment.v,
          x = this._animatorsData,
          S = this._textData,
          A = this.mHelper,
          C = this._renderType,
          T = this.renderedLetters.length,
          _ = t.l;

      if (this._hasMaskedPath) {
        if (P = this._pathData.m, !this._pathData.n || this._pathData._mdf) {
          var k,
              D = P.v;

          for (this._pathData.r && (D = D.reverse()), n = {
            tLength: 0,
            segments: []
          }, a = D._length - 1, s = g = 0; s < a; s += 1) {
            k = bez.buildBezierData(D.v[s], D.v[s + 1], [D.o[s][0] - D.v[s][0], D.o[s][1] - D.v[s][1]], [D.i[s + 1][0] - D.v[s + 1][0], D.i[s + 1][1] - D.v[s + 1][1]]), n.tLength += k.segmentLength, n.segments.push(k), g += k.segmentLength;
          }

          s = a, P.v.c && (k = bez.buildBezierData(D.v[s], D.v[0], [D.o[s][0] - D.v[s][0], D.o[s][1] - D.v[s][1]], [D.i[0][0] - D.v[0][0], D.i[0][1] - D.v[0][1]]), n.tLength += k.segmentLength, n.segments.push(k), g += k.segmentLength), this._pathData.pi = n;
        }

        if (n = this._pathData.pi, o = this._pathData.f.v, m = 1, p = !(l = f = 0), u = n.segments, o < 0 && P.v.c) for (n.tLength < Math.abs(o) && (o = -Math.abs(o) % n.tLength), m = (d = u[f = u.length - 1].points).length - 1; o < 0;) {
          o += d[m].partialLength, (m -= 1) < 0 && (m = (d = u[f -= 1].points).length - 1);
        }
        c = (d = u[f].points)[m - 1], y = (h = d[m]).partialLength;
      }

      a = _.length, i = r = 0;
      var M,
          F,
          w,
          I,
          V,
          B = 1.2 * t.finalSize * .714,
          R = !0;
      w = x.length;
      var L,
          G,
          z,
          N,
          O,
          H,
          j,
          q,
          W,
          Y,
          X,
          J,
          K = -1,
          $ = o,
          Z = f,
          U = m,
          Q = -1,
          tt = "",
          et = this.defaultPropsArray;

      if (2 === t.j || 1 === t.j) {
        var rt = 0,
            it = 0,
            st = 2 === t.j ? -.5 : -1,
            at = 0,
            nt = !0;

        for (s = 0; s < a; s += 1) {
          if (_[s].n) {
            for (rt && (rt += it); at < s;) {
              _[at].animatorJustifyOffset = rt, at += 1;
            }

            nt = !(rt = 0);
          } else {
            for (F = 0; F < w; F += 1) {
              (M = x[F].a).t.propType && (nt && 2 === t.j && (it += M.t.v * st), (V = x[F].s.getMult(_[s].anIndexes[F], S.a[F].s.totalChars)).length ? rt += M.t.v * V[0] * st : rt += M.t.v * V * st);
            }

            nt = !1;
          }
        }

        for (rt && (rt += it); at < s;) {
          _[at].animatorJustifyOffset = rt, at += 1;
        }
      }

      for (s = 0; s < a; s += 1) {
        if (A.reset(), N = 1, _[s].n) r = 0, i += t.yOffset, i += R ? 1 : 0, o = $, R = !1, this._hasMaskedPath && (m = U, c = (d = u[f = Z].points)[m - 1], y = (h = d[m]).partialLength, l = 0), J = W = X = tt = "", et = this.defaultPropsArray;else {
          if (this._hasMaskedPath) {
            if (Q !== _[s].line) {
              switch (t.j) {
                case 1:
                  o += g - t.lineWidths[_[s].line];
                  break;

                case 2:
                  o += (g - t.lineWidths[_[s].line]) / 2;
              }

              Q = _[s].line;
            }

            K !== _[s].ind && (_[K] && (o += _[K].extra), o += _[s].an / 2, K = _[s].ind), o += E[0] * _[s].an * .005;
            var ot = 0;

            for (F = 0; F < w; F += 1) {
              (M = x[F].a).p.propType && ((V = x[F].s.getMult(_[s].anIndexes[F], S.a[F].s.totalChars)).length ? ot += M.p.v[0] * V[0] : ot += M.p.v[0] * V), M.a.propType && ((V = x[F].s.getMult(_[s].anIndexes[F], S.a[F].s.totalChars)).length ? ot += M.a.v[0] * V[0] : ot += M.a.v[0] * V);
            }

            for (p = !0; p;) {
              o + ot <= l + y || !d ? (v = (o + ot - l) / h.partialLength, G = c.point[0] + (h.point[0] - c.point[0]) * v, z = c.point[1] + (h.point[1] - c.point[1]) * v, A.translate(-E[0] * _[s].an * .005, -E[1] * B * .01), p = !1) : d && (l += h.partialLength, (m += 1) >= d.length && (m = 0, d = u[f += 1] ? u[f].points : P.v.c ? u[f = m = 0].points : (l -= h.partialLength, null)), d && (c = h, y = (h = d[m]).partialLength));
            }

            L = _[s].an / 2 - _[s].add, A.translate(-L, 0, 0);
          } else L = _[s].an / 2 - _[s].add, A.translate(-L, 0, 0), A.translate(-E[0] * _[s].an * .005, -E[1] * B * .01, 0);

          for (F = 0; F < w; F += 1) {
            (M = x[F].a).t.propType && (V = x[F].s.getMult(_[s].anIndexes[F], S.a[F].s.totalChars), 0 === r && 0 === t.j || (this._hasMaskedPath ? V.length ? o += M.t.v * V[0] : o += M.t.v * V : V.length ? r += M.t.v * V[0] : r += M.t.v * V));
          }

          for (t.strokeWidthAnim && (H = t.sw || 0), t.strokeColorAnim && (O = t.sc ? [t.sc[0], t.sc[1], t.sc[2]] : [0, 0, 0]), t.fillColorAnim && t.fc && (j = [t.fc[0], t.fc[1], t.fc[2]]), F = 0; F < w; F += 1) {
            (M = x[F].a).a.propType && ((V = x[F].s.getMult(_[s].anIndexes[F], S.a[F].s.totalChars)).length ? A.translate(-M.a.v[0] * V[0], -M.a.v[1] * V[1], M.a.v[2] * V[2]) : A.translate(-M.a.v[0] * V, -M.a.v[1] * V, M.a.v[2] * V));
          }

          for (F = 0; F < w; F += 1) {
            (M = x[F].a).s.propType && ((V = x[F].s.getMult(_[s].anIndexes[F], S.a[F].s.totalChars)).length ? A.scale(1 + (M.s.v[0] - 1) * V[0], 1 + (M.s.v[1] - 1) * V[1], 1) : A.scale(1 + (M.s.v[0] - 1) * V, 1 + (M.s.v[1] - 1) * V, 1));
          }

          for (F = 0; F < w; F += 1) {
            if (M = x[F].a, V = x[F].s.getMult(_[s].anIndexes[F], S.a[F].s.totalChars), M.sk.propType && (V.length ? A.skewFromAxis(-M.sk.v * V[0], M.sa.v * V[1]) : A.skewFromAxis(-M.sk.v * V, M.sa.v * V)), M.r.propType && (V.length ? A.rotateZ(-M.r.v * V[2]) : A.rotateZ(-M.r.v * V)), M.ry.propType && (V.length ? A.rotateY(M.ry.v * V[1]) : A.rotateY(M.ry.v * V)), M.rx.propType && (V.length ? A.rotateX(M.rx.v * V[0]) : A.rotateX(M.rx.v * V)), M.o.propType && (V.length ? N += (M.o.v * V[0] - N) * V[0] : N += (M.o.v * V - N) * V), t.strokeWidthAnim && M.sw.propType && (V.length ? H += M.sw.v * V[0] : H += M.sw.v * V), t.strokeColorAnim && M.sc.propType) for (q = 0; q < 3; q += 1) {
              V.length ? O[q] += (M.sc.v[q] - O[q]) * V[0] : O[q] += (M.sc.v[q] - O[q]) * V;
            }

            if (t.fillColorAnim && t.fc) {
              if (M.fc.propType) for (q = 0; q < 3; q += 1) {
                V.length ? j[q] += (M.fc.v[q] - j[q]) * V[0] : j[q] += (M.fc.v[q] - j[q]) * V;
              }
              M.fh.propType && (j = V.length ? addHueToRGB(j, M.fh.v * V[0]) : addHueToRGB(j, M.fh.v * V)), M.fs.propType && (j = V.length ? addSaturationToRGB(j, M.fs.v * V[0]) : addSaturationToRGB(j, M.fs.v * V)), M.fb.propType && (j = V.length ? addBrightnessToRGB(j, M.fb.v * V[0]) : addBrightnessToRGB(j, M.fb.v * V));
            }
          }

          for (F = 0; F < w; F += 1) {
            (M = x[F].a).p.propType && (V = x[F].s.getMult(_[s].anIndexes[F], S.a[F].s.totalChars), this._hasMaskedPath ? V.length ? A.translate(0, M.p.v[1] * V[0], -M.p.v[2] * V[1]) : A.translate(0, M.p.v[1] * V, -M.p.v[2] * V) : V.length ? A.translate(M.p.v[0] * V[0], M.p.v[1] * V[1], -M.p.v[2] * V[2]) : A.translate(M.p.v[0] * V, M.p.v[1] * V, -M.p.v[2] * V));
          }

          if (t.strokeWidthAnim && (W = H < 0 ? 0 : H), t.strokeColorAnim && (Y = "rgb(" + Math.round(255 * O[0]) + "," + Math.round(255 * O[1]) + "," + Math.round(255 * O[2]) + ")"), t.fillColorAnim && t.fc && (X = "rgb(" + Math.round(255 * j[0]) + "," + Math.round(255 * j[1]) + "," + Math.round(255 * j[2]) + ")"), this._hasMaskedPath) {
            if (A.translate(0, -t.ls), A.translate(0, E[1] * B * .01 + i, 0), S.p.p) {
              b = (h.point[1] - c.point[1]) / (h.point[0] - c.point[0]);
              var ht = 180 * Math.atan(b) / Math.PI;
              h.point[0] < c.point[0] && (ht += 180), A.rotate(-ht * Math.PI / 180);
            }

            A.translate(G, z, 0), o -= E[0] * _[s].an * .005, _[s + 1] && K !== _[s + 1].ind && (o += _[s].an / 2, o += .001 * t.tr * t.finalSize);
          } else {
            switch (A.translate(r, i, 0), t.ps && A.translate(t.ps[0], t.ps[1] + t.ascent, 0), t.j) {
              case 1:
                A.translate(_[s].animatorJustifyOffset + t.justifyOffset + (t.boxWidth - t.lineWidths[_[s].line]), 0, 0);
                break;

              case 2:
                A.translate(_[s].animatorJustifyOffset + t.justifyOffset + (t.boxWidth - t.lineWidths[_[s].line]) / 2, 0, 0);
            }

            A.translate(0, -t.ls), A.translate(L, 0, 0), A.translate(E[0] * _[s].an * .005, E[1] * B * .01, 0), r += _[s].l + .001 * t.tr * t.finalSize;
          }

          "html" === C ? tt = A.toCSS() : "svg" === C ? tt = A.to2dCSS() : et = [A.props[0], A.props[1], A.props[2], A.props[3], A.props[4], A.props[5], A.props[6], A.props[7], A.props[8], A.props[9], A.props[10], A.props[11], A.props[12], A.props[13], A.props[14], A.props[15]], J = N;
        }
        this.lettersChangedFlag = T <= s ? (I = new LetterProps(J, W, Y, X, tt, et), this.renderedLetters.push(I), T += 1, !0) : (I = this.renderedLetters[s]).update(J, W, Y, X, tt, et) || this.lettersChangedFlag;
      }
    }
  }, TextAnimatorProperty.prototype.getValue = function () {
    this._elem.globalData.frameId !== this._frameId && (this._frameId = this._elem.globalData.frameId, this.iterateDynamicProperties());
  }, TextAnimatorProperty.prototype.mHelper = new Matrix(), TextAnimatorProperty.prototype.defaultPropsArray = [], extendPrototype([DynamicPropertyContainer], TextAnimatorProperty), LetterProps.prototype.update = function (t, e, r, i, s, a) {
    this._mdf.o = !1, this._mdf.sw = !1, this._mdf.sc = !1, this._mdf.fc = !1, this._mdf.m = !1;
    var n = this._mdf.p = !1;
    return this.o !== t && (this.o = t, n = this._mdf.o = !0), this.sw !== e && (this.sw = e, n = this._mdf.sw = !0), this.sc !== r && (this.sc = r, n = this._mdf.sc = !0), this.fc !== i && (this.fc = i, n = this._mdf.fc = !0), this.m !== s && (this.m = s, n = this._mdf.m = !0), !a.length || this.p[0] === a[0] && this.p[1] === a[1] && this.p[4] === a[4] && this.p[5] === a[5] && this.p[12] === a[12] && this.p[13] === a[13] || (this.p = a, n = this._mdf.p = !0), n;
  }, TextProperty.prototype.defaultBoxWidth = [0, 0], TextProperty.prototype.copyData = function (t, e) {
    for (var r in e) {
      Object.prototype.hasOwnProperty.call(e, r) && (t[r] = e[r]);
    }

    return t;
  }, TextProperty.prototype.setCurrentData = function (t) {
    t.__complete || this.completeTextData(t), this.currentData = t, this.currentData.boxWidth = this.currentData.boxWidth || this.defaultBoxWidth, this._mdf = !0;
  }, TextProperty.prototype.searchProperty = function () {
    return this.searchKeyframes();
  }, TextProperty.prototype.searchKeyframes = function () {
    return this.kf = 1 < this.data.d.k.length, this.kf && this.addEffect(this.getKeyframeValue.bind(this)), this.kf;
  }, TextProperty.prototype.addEffect = function (t) {
    this.effectsSequence.push(t), this.elem.addDynamicProperty(this);
  }, TextProperty.prototype.getValue = function (t) {
    if (this.elem.globalData.frameId !== this.frameId && this.effectsSequence.length || t) {
      this.currentData.t = this.data.d.k[this.keysIndex].s.t;
      var e = this.currentData,
          r = this.keysIndex;
      if (this.lock) this.setCurrentData(this.currentData);else {
        var i;
        this.lock = !0, this._mdf = !1;
        var s = this.effectsSequence.length,
            a = t || this.data.d.k[this.keysIndex].s;

        for (i = 0; i < s; i += 1) {
          a = r !== this.keysIndex ? this.effectsSequence[i](a, a.t) : this.effectsSequence[i](this.currentData, a.t);
        }

        e !== a && this.setCurrentData(a), this.v = this.currentData, this.pv = this.v, this.lock = !1, this.frameId = this.elem.globalData.frameId;
      }
    }
  }, TextProperty.prototype.getKeyframeValue = function () {
    for (var t = this.data.d.k, e = this.elem.comp.renderedFrame, r = 0, i = t.length; r <= i - 1 && !(r === i - 1 || t[r + 1].t > e);) {
      r += 1;
    }

    return this.keysIndex !== r && (this.keysIndex = r), this.data.d.k[this.keysIndex].s;
  }, TextProperty.prototype.buildFinalText = function (t) {
    for (var e, r, i = [], s = 0, a = t.length, n = !1; s < a;) {
      e = t.charCodeAt(s), FontManager.isCombinedCharacter(e) ? i[i.length - 1] += t.charAt(s) : 55296 <= e && e <= 56319 ? 56320 <= (r = t.charCodeAt(s + 1)) && r <= 57343 ? (n || FontManager.isModifier(e, r) ? (i[i.length - 1] += t.substr(s, 2), n = !1) : i.push(t.substr(s, 2)), s += 1) : i.push(t.charAt(s)) : 56319 < e ? (r = t.charCodeAt(s + 1), FontManager.isZeroWidthJoiner(e, r) ? (n = !0, i[i.length - 1] += t.substr(s, 2), s += 1) : i.push(t.charAt(s))) : FontManager.isZeroWidthJoiner(e) ? (i[i.length - 1] += t.charAt(s), n = !0) : i.push(t.charAt(s)), s += 1;
    }

    return i;
  }, TextProperty.prototype.completeTextData = function (t) {
    t.__complete = !0;
    var e,
        r,
        i,
        s,
        a,
        n,
        o,
        h = this.elem.globalData.fontManager,
        l = this.data,
        p = [],
        m = 0,
        f = l.m.g,
        c = 0,
        d = 0,
        u = 0,
        y = [],
        g = 0,
        v = 0,
        b = h.getFontByName(t.f),
        P = 0,
        E = getFontProperties(b);
    t.fWeight = E.weight, t.fStyle = E.style, t.finalSize = t.s, t.finalText = this.buildFinalText(t.t), r = t.finalText.length, t.finalLineHeight = t.lh;
    var x,
        S = t.tr / 1e3 * t.finalSize;
    if (t.sz) for (var A, C, T = !0, _ = t.sz[0], k = t.sz[1]; T;) {
      g = A = 0, r = (C = this.buildFinalText(t.t)).length, S = t.tr / 1e3 * t.finalSize;
      var D = -1;

      for (e = 0; e < r; e += 1) {
        x = C[e].charCodeAt(0), i = !1, " " === C[e] ? D = e : 13 !== x && 3 !== x || (i = !(g = 0), A += t.finalLineHeight || 1.2 * t.finalSize), _ < g + (P = h.chars ? (o = h.getCharData(C[e], b.fStyle, b.fFamily), i ? 0 : o.w * t.finalSize / 100) : h.measureText(C[e], t.f, t.finalSize)) && " " !== C[e] ? (-1 === D ? r += 1 : e = D, A += t.finalLineHeight || 1.2 * t.finalSize, C.splice(e, D === e ? 1 : 0, "\r"), D = -1, g = 0) : (g += P, g += S);
      }

      A += b.ascent * t.finalSize / 100, this.canResize && t.finalSize > this.minimumFontSize && k < A ? (t.finalSize -= 1, t.finalLineHeight = t.finalSize * t.lh / t.s) : (t.finalText = C, r = t.finalText.length, T = !1);
    }
    g = -S;
    var M,
        F = P = 0;

    for (e = 0; e < r; e += 1) {
      if (i = !1, 13 === (x = (M = t.finalText[e]).charCodeAt(0)) || 3 === x ? (F = 0, y.push(g), v = v < g ? g : v, g = -2 * S, i = !(s = ""), u += 1) : s = M, P = h.chars ? (o = h.getCharData(M, b.fStyle, h.getFontByName(t.f).fFamily), i ? 0 : o.w * t.finalSize / 100) : h.measureText(s, t.f, t.finalSize), " " === M ? F += P + S : (g += P + S + F, F = 0), p.push({
        l: P,
        an: P,
        add: c,
        n: i,
        anIndexes: [],
        val: s,
        line: u,
        animatorJustifyOffset: 0
      }), 2 == f) {
        if (c += P, "" === s || " " === s || e === r - 1) {
          for ("" !== s && " " !== s || (c -= P); d <= e;) {
            p[d].an = c, p[d].ind = m, p[d].extra = P, d += 1;
          }

          m += 1, c = 0;
        }
      } else if (3 == f) {
        if (c += P, "" === s || e === r - 1) {
          for ("" === s && (c -= P); d <= e;) {
            p[d].an = c, p[d].ind = m, p[d].extra = P, d += 1;
          }

          c = 0, m += 1;
        }
      } else p[m].ind = m, p[m].extra = 0, m += 1;
    }

    if (t.l = p, v = v < g ? g : v, y.push(g), t.sz) t.boxWidth = t.sz[0], t.justifyOffset = 0;else switch (t.boxWidth = v, t.j) {
      case 1:
        t.justifyOffset = -t.boxWidth;
        break;

      case 2:
        t.justifyOffset = -t.boxWidth / 2;
        break;

      default:
        t.justifyOffset = 0;
    }
    t.lineWidths = y;
    var w,
        I,
        V,
        B,
        R = l.a;
    n = R.length;
    var L = [];

    for (a = 0; a < n; a += 1) {
      for ((w = R[a]).a.sc && (t.strokeColorAnim = !0), w.a.sw && (t.strokeWidthAnim = !0), (w.a.fc || w.a.fh || w.a.fs || w.a.fb) && (t.fillColorAnim = !0), B = 0, V = w.s.b, e = 0; e < r; e += 1) {
        (I = p[e]).anIndexes[a] = B, (1 == V && "" !== I.val || 2 == V && "" !== I.val && " " !== I.val || 3 == V && (I.n || " " == I.val || e == r - 1) || 4 == V && (I.n || e == r - 1)) && (1 === w.s.rn && L.push(B), B += 1);
      }

      l.a[a].s.totalChars = B;
      var G,
          z = -1;
      if (1 === w.s.rn) for (e = 0; e < r; e += 1) {
        z != (I = p[e]).anIndexes[a] && (z = I.anIndexes[a], G = L.splice(Math.floor(Math.random() * L.length), 1)[0]), I.anIndexes[a] = G;
      }
    }

    t.yOffset = t.finalLineHeight || 1.2 * t.finalSize, t.ls = t.ls || 0, t.ascent = b.ascent * t.finalSize / 100;
  }, TextProperty.prototype.updateDocumentData = function (t, e) {
    e = void 0 === e ? this.keysIndex : e;
    var r = this.copyData({}, this.data.d.k[e].s);
    r = this.copyData(r, t), this.data.d.k[e].s = r, this.recalculate(e), this.elem.addDynamicProperty(this);
  }, TextProperty.prototype.recalculate = function (t) {
    var e = this.data.d.k[t].s;
    e.__complete = !1, this.keysIndex = 0, this._isFirstFrame = !0, this.getValue(e);
  }, TextProperty.prototype.canResizeFont = function (t) {
    this.canResize = t, this.recalculate(this.keysIndex), this.elem.addDynamicProperty(this);
  }, TextProperty.prototype.setMinimumFontSize = function (t) {
    this.minimumFontSize = Math.floor(t) || 1, this.recalculate(this.keysIndex), this.elem.addDynamicProperty(this);
  };

  var TextSelectorProp = function () {
    var c = Math.max,
        d = Math.min,
        u = Math.floor;

    function i(t, e) {
      this._currentTextLength = -1, this.k = !1, this.data = e, this.elem = t, this.comp = t.comp, this.finalS = 0, this.finalE = 0, this.initDynamicPropertyContainer(t), this.s = PropertyFactory.getProp(t, e.s || {
        k: 0
      }, 0, 0, this), this.e = "e" in e ? PropertyFactory.getProp(t, e.e, 0, 0, this) : {
        v: 100
      }, this.o = PropertyFactory.getProp(t, e.o || {
        k: 0
      }, 0, 0, this), this.xe = PropertyFactory.getProp(t, e.xe || {
        k: 0
      }, 0, 0, this), this.ne = PropertyFactory.getProp(t, e.ne || {
        k: 0
      }, 0, 0, this), this.a = PropertyFactory.getProp(t, e.a, 0, .01, this), this.dynamicProperties.length || this.getValue();
    }

    return i.prototype = {
      getMult: function getMult(t) {
        this._currentTextLength !== this.elem.textProperty.currentData.l.length && this.getValue();
        var e = 0,
            r = 0,
            i = 1,
            s = 1;
        0 < this.ne.v ? e = this.ne.v / 100 : r = -this.ne.v / 100, 0 < this.xe.v ? i = 1 - this.xe.v / 100 : s = 1 + this.xe.v / 100;
        var a = BezierFactory.getBezierEasing(e, r, i, s).get,
            n = 0,
            o = this.finalS,
            h = this.finalE,
            l = this.data.sh;
        if (2 === l) n = a(n = h === o ? h <= t ? 1 : 0 : c(0, d(.5 / (h - o) + (t - o) / (h - o), 1)));else if (3 === l) n = a(n = h === o ? h <= t ? 0 : 1 : 1 - c(0, d(.5 / (h - o) + (t - o) / (h - o), 1)));else if (4 === l) h === o ? n = 0 : (n = c(0, d(.5 / (h - o) + (t - o) / (h - o), 1))) < .5 ? n *= 2 : n = 1 - 2 * (n - .5), n = a(n);else if (5 === l) {
          if (h === o) n = 0;else {
            var p = h - o,
                m = -p / 2 + (t = d(c(0, t + .5 - o), h - o)),
                f = p / 2;
            n = Math.sqrt(1 - m * m / (f * f));
          }
          n = a(n);
        } else n = 6 === l ? a(n = h === o ? 0 : (t = d(c(0, t + .5 - o), h - o), (1 + Math.cos(Math.PI + 2 * Math.PI * t / (h - o))) / 2)) : (t >= u(o) && (n = c(0, d(t - o < 0 ? d(h, 1) - (o - t) : h - t, 1))), a(n));
        return n * this.a.v;
      },
      getValue: function getValue(t) {
        this.iterateDynamicProperties(), this._mdf = t || this._mdf, this._currentTextLength = this.elem.textProperty.currentData.l.length || 0, t && 2 === this.data.r && (this.e.v = this._currentTextLength);
        var e = 2 === this.data.r ? 1 : 100 / this.data.totalChars,
            r = this.o.v / e,
            i = this.s.v / e + r,
            s = this.e.v / e + r;

        if (s < i) {
          var a = i;
          i = s, s = a;
        }

        this.finalS = i, this.finalE = s;
      }
    }, extendPrototype([DynamicPropertyContainer], i), {
      getTextSelectorProp: function getTextSelectorProp(t, e, r) {
        return new i(t, e, r);
      }
    };
  }(),
      poolFactory = function poolFactory(t, e, r) {
    var i = 0,
        s = t,
        a = createSizedArray(s);
    return {
      newElement: function newElement() {
        return i ? a[i -= 1] : e();
      },
      release: function release(t) {
        i === s && (a = pooling["double"](a), s *= 2), r && r(t), a[i] = t, i += 1;
      }
    };
  },
      pooling = {
    "double": function double(t) {
      return t.concat(createSizedArray(t.length));
    }
  },
      pointPool = poolFactory(8, function () {
    return createTypedArray("float32", 2);
  }),
      shapePool = (IB = poolFactory(4, function () {
    return new ShapePath();
  }, function (t) {
    var e,
        r = t._length;

    for (e = 0; e < r; e += 1) {
      pointPool.release(t.v[e]), pointPool.release(t.i[e]), pointPool.release(t.o[e]), t.v[e] = null, t.i[e] = null, t.o[e] = null;
    }

    t._length = 0, t.c = !1;
  }), IB.clone = function (t) {
    var e,
        r = IB.newElement(),
        i = void 0 === t._length ? t.v.length : t._length;

    for (r.setLength(i), r.c = t.c, e = 0; e < i; e += 1) {
      r.setTripleAt(t.v[e][0], t.v[e][1], t.o[e][0], t.o[e][1], t.i[e][0], t.i[e][1], e);
    }

    return r;
  }, IB),
      IB,
      shapeCollectionPool = (QB = {
    newShapeCollection: function newShapeCollection() {
      var t;
      t = RB ? TB[RB -= 1] : new ShapeCollection();
      return t;
    },
    release: function release(t) {
      var e,
          r = t._length;

      for (e = 0; e < r; e += 1) {
        shapePool.release(t.shapes[e]);
      }

      t._length = 0, RB === SB && (TB = pooling["double"](TB), SB *= 2);
      TB[RB] = t, RB += 1;
    }
  }, RB = 0, SB = 4, TB = createSizedArray(SB), QB),
      QB,
      RB,
      SB,
      TB,
      segmentsLengthPool = poolFactory(8, function () {
    return {
      lengths: [],
      totalLength: 0
    };
  }, function (t) {
    var e,
        r = t.lengths.length;

    for (e = 0; e < r; e += 1) {
      bezierLengthPool.release(t.lengths[e]);
    }

    t.lengths.length = 0;
  }),
      bezierLengthPool = poolFactory(8, function () {
    return {
      addedLength: 0,
      percents: createTypedArray("float32", defaultCurveSegments),
      lengths: createTypedArray("float32", defaultCurveSegments)
    };
  }),
      markerParser = function () {
    function a(t) {
      for (var e, r = t.split("\r\n"), i = {}, s = 0, a = 0; a < r.length; a += 1) {
        2 === (e = r[a].split(":")).length && (i[e[0]] = e[1].trim(), s += 1);
      }

      if (0 === s) throw new Error();
      return i;
    }

    return function (e) {
      for (var t = [], r = 0; r < e.length; r += 1) {
        var i = e[r],
            s = {
          time: i.tm,
          duration: i.dr
        };

        try {
          s.payload = JSON.parse(e[r].cm);
        } catch (t) {
          try {
            s.payload = a(e[r].cm);
          } catch (t) {
            s.payload = {
              name: e[r]
            };
          }
        }

        t.push(s);
      }

      return t;
    };
  }();

  function BaseRenderer() {}

  function SVGRenderer(t, e) {
    this.animationItem = t, this.layers = null, this.renderedFrame = -1, this.svgElement = createNS("svg");
    var r = "";

    if (e && e.title) {
      var i = createNS("title"),
          s = createElementID();
      i.setAttribute("id", s), i.textContent = e.title, this.svgElement.appendChild(i), r += s;
    }

    if (e && e.description) {
      var a = createNS("desc"),
          n = createElementID();
      a.setAttribute("id", n), a.textContent = e.description, this.svgElement.appendChild(a), r += " " + n;
    }

    r && this.svgElement.setAttribute("aria-labelledby", r);
    var o = createNS("defs");
    this.svgElement.appendChild(o);
    var h = createNS("g");
    this.svgElement.appendChild(h), this.layerElement = h, this.renderConfig = {
      preserveAspectRatio: e && e.preserveAspectRatio || "xMidYMid meet",
      imagePreserveAspectRatio: e && e.imagePreserveAspectRatio || "xMidYMid slice",
      progressiveLoad: e && e.progressiveLoad || !1,
      hideOnTransparent: !(e && !1 === e.hideOnTransparent),
      viewBoxOnly: e && e.viewBoxOnly || !1,
      viewBoxSize: e && e.viewBoxSize || !1,
      className: e && e.className || "",
      id: e && e.id || "",
      focusable: e && e.focusable,
      filterSize: {
        width: e && e.filterSize && e.filterSize.width || "100%",
        height: e && e.filterSize && e.filterSize.height || "100%",
        x: e && e.filterSize && e.filterSize.x || "0%",
        y: e && e.filterSize && e.filterSize.y || "0%"
      }
    }, this.globalData = {
      _mdf: !1,
      frameNum: -1,
      defs: o,
      renderConfig: this.renderConfig
    }, this.elements = [], this.pendingElements = [], this.destroyed = !1, this.rendererType = "svg";
  }

  function CanvasRenderer(t, e) {
    this.animationItem = t, this.renderConfig = {
      clearCanvas: !e || void 0 === e.clearCanvas || e.clearCanvas,
      context: e && e.context || null,
      progressiveLoad: e && e.progressiveLoad || !1,
      preserveAspectRatio: e && e.preserveAspectRatio || "xMidYMid meet",
      imagePreserveAspectRatio: e && e.imagePreserveAspectRatio || "xMidYMid slice",
      className: e && e.className || "",
      id: e && e.id || ""
    }, this.renderConfig.dpr = e && e.dpr || 1, this.animationItem.wrapper && (this.renderConfig.dpr = e && e.dpr || window.devicePixelRatio || 1), this.renderedFrame = -1, this.globalData = {
      frameNum: -1,
      _mdf: !1,
      renderConfig: this.renderConfig,
      currentGlobalAlpha: -1
    }, this.contextData = new CVContextData(), this.elements = [], this.pendingElements = [], this.transformMat = new Matrix(), this.completeLayers = !1, this.rendererType = "canvas";
  }

  function HybridRenderer(t, e) {
    this.animationItem = t, this.layers = null, this.renderedFrame = -1, this.renderConfig = {
      className: e && e.className || "",
      imagePreserveAspectRatio: e && e.imagePreserveAspectRatio || "xMidYMid slice",
      hideOnTransparent: !(e && !1 === e.hideOnTransparent),
      filterSize: {
        width: e && e.filterSize && e.filterSize.width || "400%",
        height: e && e.filterSize && e.filterSize.height || "400%",
        x: e && e.filterSize && e.filterSize.x || "-100%",
        y: e && e.filterSize && e.filterSize.y || "-100%"
      }
    }, this.globalData = {
      _mdf: !1,
      frameNum: -1,
      renderConfig: this.renderConfig
    }, this.pendingElements = [], this.elements = [], this.threeDElements = [], this.destroyed = !1, this.camera = null, this.supports3d = !0, this.rendererType = "html";
  }

  function MaskElement(t, e, r) {
    this.data = t, this.element = e, this.globalData = r, this.storedData = [], this.masksProperties = this.data.masksProperties || [], this.maskElement = null;
    var i,
        s,
        a = this.globalData.defs,
        n = this.masksProperties ? this.masksProperties.length : 0;
    this.viewData = createSizedArray(n), this.solidPath = "";
    var o,
        h,
        l,
        p,
        m,
        f,
        c = this.masksProperties,
        d = 0,
        u = [],
        y = createElementID(),
        g = "clipPath",
        v = "clip-path";

    for (i = 0; i < n; i += 1) {
      if (("a" !== c[i].mode && "n" !== c[i].mode || c[i].inv || 100 !== c[i].o.k || c[i].o.x) && (v = g = "mask"), "s" !== c[i].mode && "i" !== c[i].mode || 0 !== d ? l = null : ((l = createNS("rect")).setAttribute("fill", "#ffffff"), l.setAttribute("width", this.element.comp.data.w || 0), l.setAttribute("height", this.element.comp.data.h || 0), u.push(l)), s = createNS("path"), "n" === c[i].mode) this.viewData[i] = {
        op: PropertyFactory.getProp(this.element, c[i].o, 0, .01, this.element),
        prop: ShapePropertyFactory.getShapeProp(this.element, c[i], 3),
        elem: s,
        lastPath: ""
      }, a.appendChild(s);else {
        var b;

        if (d += 1, s.setAttribute("fill", "s" === c[i].mode ? "#000000" : "#ffffff"), s.setAttribute("clip-rule", "nonzero"), 0 !== c[i].x.k ? (v = g = "mask", f = PropertyFactory.getProp(this.element, c[i].x, 0, null, this.element), b = createElementID(), (p = createNS("filter")).setAttribute("id", b), (m = createNS("feMorphology")).setAttribute("operator", "erode"), m.setAttribute("in", "SourceGraphic"), m.setAttribute("radius", "0"), p.appendChild(m), a.appendChild(p), s.setAttribute("stroke", "s" === c[i].mode ? "#000000" : "#ffffff")) : f = m = null, this.storedData[i] = {
          elem: s,
          x: f,
          expan: m,
          lastPath: "",
          lastOperator: "",
          filterId: b,
          lastRadius: 0
        }, "i" === c[i].mode) {
          h = u.length;
          var P = createNS("g");

          for (o = 0; o < h; o += 1) {
            P.appendChild(u[o]);
          }

          var E = createNS("mask");
          E.setAttribute("mask-type", "alpha"), E.setAttribute("id", y + "_" + d), E.appendChild(s), a.appendChild(E), P.setAttribute("mask", "url(" + locationHref + "#" + y + "_" + d + ")"), u.length = 0, u.push(P);
        } else u.push(s);

        c[i].inv && !this.solidPath && (this.solidPath = this.createLayerSolidPath()), this.viewData[i] = {
          elem: s,
          lastPath: "",
          op: PropertyFactory.getProp(this.element, c[i].o, 0, .01, this.element),
          prop: ShapePropertyFactory.getShapeProp(this.element, c[i], 3),
          invRect: l
        }, this.viewData[i].prop.k || this.drawPath(c[i], this.viewData[i].prop.v, this.viewData[i]);
      }
    }

    for (this.maskElement = createNS(g), n = u.length, i = 0; i < n; i += 1) {
      this.maskElement.appendChild(u[i]);
    }

    0 < d && (this.maskElement.setAttribute("id", y), this.element.maskedElement.setAttribute(v, "url(" + locationHref + "#" + y + ")"), a.appendChild(this.maskElement)), this.viewData.length && this.element.addRenderableComponent(this);
  }

  function HierarchyElement() {}

  function FrameElement() {}

  function TransformElement() {}

  function RenderableElement() {}

  function RenderableDOMElement() {}

  function ProcessedElement(t, e) {
    this.elem = t, this.pos = e;
  }

  function SVGStyleData(t, e) {
    this.data = t, this.type = t.ty, this.d = "", this.lvl = e, this._mdf = !1, this.closed = !0 === t.hd, this.pElem = createNS("path"), this.msElem = null;
  }

  function SVGShapeData(t, e, r) {
    this.caches = [], this.styles = [], this.transformers = t, this.lStr = "", this.sh = r, this.lvl = e, this._isAnimated = !!r.k;

    for (var i = 0, s = t.length; i < s;) {
      if (t[i].mProps.dynamicProperties.length) {
        this._isAnimated = !0;
        break;
      }

      i += 1;
    }
  }

  function SVGTransformData(t, e, r) {
    this.transform = {
      mProps: t,
      op: e,
      container: r
    }, this.elements = [], this._isAnimated = this.transform.mProps.dynamicProperties.length || this.transform.op.effectsSequence.length;
  }

  function SVGStrokeStyleData(t, e, r) {
    this.initDynamicPropertyContainer(t), this.getValue = this.iterateDynamicProperties, this.o = PropertyFactory.getProp(t, e.o, 0, .01, this), this.w = PropertyFactory.getProp(t, e.w, 0, null, this), this.d = new DashProperty(t, e.d || {}, "svg", this), this.c = PropertyFactory.getProp(t, e.c, 1, 255, this), this.style = r, this._isAnimated = !!this._isAnimated;
  }

  function SVGFillStyleData(t, e, r) {
    this.initDynamicPropertyContainer(t), this.getValue = this.iterateDynamicProperties, this.o = PropertyFactory.getProp(t, e.o, 0, .01, this), this.c = PropertyFactory.getProp(t, e.c, 1, 255, this), this.style = r;
  }

  function SVGGradientFillStyleData(t, e, r) {
    this.initDynamicPropertyContainer(t), this.getValue = this.iterateDynamicProperties, this.initGradientData(t, e, r);
  }

  function SVGGradientStrokeStyleData(t, e, r) {
    this.initDynamicPropertyContainer(t), this.getValue = this.iterateDynamicProperties, this.w = PropertyFactory.getProp(t, e.w, 0, null, this), this.d = new DashProperty(t, e.d || {}, "svg", this), this.initGradientData(t, e, r), this._isAnimated = !!this._isAnimated;
  }

  function ShapeGroupData() {
    this.it = [], this.prevViewData = [], this.gr = createNS("g");
  }

  BaseRenderer.prototype.checkLayers = function (t) {
    var e,
        r,
        i = this.layers.length;

    for (this.completeLayers = !0, e = i - 1; 0 <= e; e -= 1) {
      this.elements[e] || (r = this.layers[e]).ip - r.st <= t - this.layers[e].st && r.op - r.st > t - this.layers[e].st && this.buildItem(e), this.completeLayers = !!this.elements[e] && this.completeLayers;
    }

    this.checkPendingElements();
  }, BaseRenderer.prototype.createItem = function (t) {
    switch (t.ty) {
      case 2:
        return this.createImage(t);

      case 0:
        return this.createComp(t);

      case 1:
        return this.createSolid(t);

      case 3:
        return this.createNull(t);

      case 4:
        return this.createShape(t);

      case 5:
        return this.createText(t);

      case 6:
        return this.createAudio(t);

      case 13:
        return this.createCamera(t);

      case 15:
        return this.createFootage(t);

      default:
        return this.createNull(t);
    }
  }, BaseRenderer.prototype.createCamera = function () {
    throw new Error("You're using a 3d camera. Try the html renderer.");
  }, BaseRenderer.prototype.createAudio = function (t) {
    return new AudioElement(t, this.globalData, this);
  }, BaseRenderer.prototype.createFootage = function (t) {
    return new FootageElement(t, this.globalData, this);
  }, BaseRenderer.prototype.buildAllItems = function () {
    var t,
        e = this.layers.length;

    for (t = 0; t < e; t += 1) {
      this.buildItem(t);
    }

    this.checkPendingElements();
  }, BaseRenderer.prototype.includeLayers = function (t) {
    var e;
    this.completeLayers = !1;
    var r,
        i = t.length,
        s = this.layers.length;

    for (e = 0; e < i; e += 1) {
      for (r = 0; r < s;) {
        if (this.layers[r].id === t[e].id) {
          this.layers[r] = t[e];
          break;
        }

        r += 1;
      }
    }
  }, BaseRenderer.prototype.setProjectInterface = function (t) {
    this.globalData.projectInterface = t;
  }, BaseRenderer.prototype.initItems = function () {
    this.globalData.progressiveLoad || this.buildAllItems();
  }, BaseRenderer.prototype.buildElementParenting = function (t, e, r) {
    for (var i = this.elements, s = this.layers, a = 0, n = s.length; a < n;) {
      s[a].ind == e && (i[a] && !0 !== i[a] ? (r.push(i[a]), i[a].setAsParent(), void 0 !== s[a].parent ? this.buildElementParenting(t, s[a].parent, r) : t.setHierarchy(r)) : (this.buildItem(a), this.addPendingElement(t))), a += 1;
    }
  }, BaseRenderer.prototype.addPendingElement = function (t) {
    this.pendingElements.push(t);
  }, BaseRenderer.prototype.searchExtraCompositions = function (t) {
    var e,
        r = t.length;

    for (e = 0; e < r; e += 1) {
      if (t[e].xt) {
        var i = this.createComp(t[e]);
        i.initExpressions(), this.globalData.projectInterface.registerComposition(i);
      }
    }
  }, BaseRenderer.prototype.setupGlobalData = function (t, e) {
    this.globalData.fontManager = new FontManager(), this.globalData.fontManager.addChars(t.chars), this.globalData.fontManager.addFonts(t.fonts, e), this.globalData.getAssetData = this.animationItem.getAssetData.bind(this.animationItem), this.globalData.getAssetsPath = this.animationItem.getAssetsPath.bind(this.animationItem), this.globalData.imageLoader = this.animationItem.imagePreloader, this.globalData.audioController = this.animationItem.audioController, this.globalData.frameId = 0, this.globalData.frameRate = t.fr, this.globalData.nm = t.nm, this.globalData.compSize = {
      w: t.w,
      h: t.h
    };
  }, extendPrototype([BaseRenderer], SVGRenderer), SVGRenderer.prototype.createNull = function (t) {
    return new NullElement(t, this.globalData, this);
  }, SVGRenderer.prototype.createShape = function (t) {
    return new SVGShapeElement(t, this.globalData, this);
  }, SVGRenderer.prototype.createText = function (t) {
    return new SVGTextLottieElement(t, this.globalData, this);
  }, SVGRenderer.prototype.createImage = function (t) {
    return new IImageElement(t, this.globalData, this);
  }, SVGRenderer.prototype.createComp = function (t) {
    return new SVGCompElement(t, this.globalData, this);
  }, SVGRenderer.prototype.createSolid = function (t) {
    return new ISolidElement(t, this.globalData, this);
  }, SVGRenderer.prototype.configAnimation = function (t) {
    this.svgElement.setAttribute("xmlns", "http://www.w3.org/2000/svg"), this.renderConfig.viewBoxSize ? this.svgElement.setAttribute("viewBox", this.renderConfig.viewBoxSize) : this.svgElement.setAttribute("viewBox", "0 0 " + t.w + " " + t.h), this.renderConfig.viewBoxOnly || (this.svgElement.setAttribute("width", t.w), this.svgElement.setAttribute("height", t.h), this.svgElement.style.width = "100%", this.svgElement.style.height = "100%", this.svgElement.style.transform = "translate3d(0,0,0)"), this.renderConfig.className && this.svgElement.setAttribute("class", this.renderConfig.className), this.renderConfig.id && this.svgElement.setAttribute("id", this.renderConfig.id), void 0 !== this.renderConfig.focusable && this.svgElement.setAttribute("focusable", this.renderConfig.focusable), this.svgElement.setAttribute("preserveAspectRatio", this.renderConfig.preserveAspectRatio), this.animationItem.wrapper.appendChild(this.svgElement);
    var e = this.globalData.defs;
    this.setupGlobalData(t, e), this.globalData.progressiveLoad = this.renderConfig.progressiveLoad, this.data = t;
    var r = createNS("clipPath"),
        i = createNS("rect");
    i.setAttribute("width", t.w), i.setAttribute("height", t.h), i.setAttribute("x", 0), i.setAttribute("y", 0);
    var s = createElementID();
    r.setAttribute("id", s), r.appendChild(i), this.layerElement.setAttribute("clip-path", "url(" + locationHref + "#" + s + ")"), e.appendChild(r), this.layers = t.layers, this.elements = createSizedArray(t.layers.length);
  }, SVGRenderer.prototype.destroy = function () {
    var t;
    this.animationItem.wrapper && (this.animationItem.wrapper.innerText = ""), this.layerElement = null, this.globalData.defs = null;
    var e = this.layers ? this.layers.length : 0;

    for (t = 0; t < e; t += 1) {
      this.elements[t] && this.elements[t].destroy();
    }

    this.elements.length = 0, this.destroyed = !0, this.animationItem = null;
  }, SVGRenderer.prototype.updateContainerSize = function () {}, SVGRenderer.prototype.buildItem = function (t) {
    var e = this.elements;

    if (!e[t] && 99 !== this.layers[t].ty) {
      e[t] = !0;
      var r = this.createItem(this.layers[t]);
      e[t] = r, expressionsPlugin && (0 === this.layers[t].ty && this.globalData.projectInterface.registerComposition(r), r.initExpressions()), this.appendElementInPos(r, t), this.layers[t].tt && (this.elements[t - 1] && !0 !== this.elements[t - 1] ? r.setMatte(e[t - 1].layerId) : (this.buildItem(t - 1), this.addPendingElement(r)));
    }
  }, SVGRenderer.prototype.checkPendingElements = function () {
    for (; this.pendingElements.length;) {
      var t = this.pendingElements.pop();
      if (t.checkParenting(), t.data.tt) for (var e = 0, r = this.elements.length; e < r;) {
        if (this.elements[e] === t) {
          t.setMatte(this.elements[e - 1].layerId);
          break;
        }

        e += 1;
      }
    }
  }, SVGRenderer.prototype.renderFrame = function (t) {
    if (this.renderedFrame !== t && !this.destroyed) {
      var e;
      null === t ? t = this.renderedFrame : this.renderedFrame = t, this.globalData.frameNum = t, this.globalData.frameId += 1, this.globalData.projectInterface.currentFrame = t, this.globalData._mdf = !1;
      var r = this.layers.length;

      for (this.completeLayers || this.checkLayers(t), e = r - 1; 0 <= e; e -= 1) {
        (this.completeLayers || this.elements[e]) && this.elements[e].prepareFrame(t - this.layers[e].st);
      }

      if (this.globalData._mdf) for (e = 0; e < r; e += 1) {
        (this.completeLayers || this.elements[e]) && this.elements[e].renderFrame();
      }
    }
  }, SVGRenderer.prototype.appendElementInPos = function (t, e) {
    var r = t.getBaseElement();

    if (r) {
      for (var i, s = 0; s < e;) {
        this.elements[s] && !0 !== this.elements[s] && this.elements[s].getBaseElement() && (i = this.elements[s].getBaseElement()), s += 1;
      }

      i ? this.layerElement.insertBefore(r, i) : this.layerElement.appendChild(r);
    }
  }, SVGRenderer.prototype.hide = function () {
    this.layerElement.style.display = "none";
  }, SVGRenderer.prototype.show = function () {
    this.layerElement.style.display = "block";
  }, extendPrototype([BaseRenderer], CanvasRenderer), CanvasRenderer.prototype.createShape = function (t) {
    return new CVShapeElement(t, this.globalData, this);
  }, CanvasRenderer.prototype.createText = function (t) {
    return new CVTextElement(t, this.globalData, this);
  }, CanvasRenderer.prototype.createImage = function (t) {
    return new CVImageElement(t, this.globalData, this);
  }, CanvasRenderer.prototype.createComp = function (t) {
    return new CVCompElement(t, this.globalData, this);
  }, CanvasRenderer.prototype.createSolid = function (t) {
    return new CVSolidElement(t, this.globalData, this);
  }, CanvasRenderer.prototype.createNull = SVGRenderer.prototype.createNull, CanvasRenderer.prototype.ctxTransform = function (t) {
    if (1 !== t[0] || 0 !== t[1] || 0 !== t[4] || 1 !== t[5] || 0 !== t[12] || 0 !== t[13]) if (this.renderConfig.clearCanvas) {
      this.transformMat.cloneFromProps(t);
      var e = this.contextData.cTr.props;
      this.transformMat.transform(e[0], e[1], e[2], e[3], e[4], e[5], e[6], e[7], e[8], e[9], e[10], e[11], e[12], e[13], e[14], e[15]), this.contextData.cTr.cloneFromProps(this.transformMat.props);
      var r = this.contextData.cTr.props;
      this.canvasContext.setTransform(r[0], r[1], r[4], r[5], r[12], r[13]);
    } else this.canvasContext.transform(t[0], t[1], t[4], t[5], t[12], t[13]);
  }, CanvasRenderer.prototype.ctxOpacity = function (t) {
    if (!this.renderConfig.clearCanvas) return this.canvasContext.globalAlpha *= t < 0 ? 0 : t, void (this.globalData.currentGlobalAlpha = this.contextData.cO);
    this.contextData.cO *= t < 0 ? 0 : t, this.globalData.currentGlobalAlpha !== this.contextData.cO && (this.canvasContext.globalAlpha = this.contextData.cO, this.globalData.currentGlobalAlpha = this.contextData.cO);
  }, CanvasRenderer.prototype.reset = function () {
    this.renderConfig.clearCanvas ? this.contextData.reset() : this.canvasContext.restore();
  }, CanvasRenderer.prototype.save = function (t) {
    if (this.renderConfig.clearCanvas) {
      t && this.canvasContext.save();
      var e,
          r = this.contextData.cTr.props;
      this.contextData._length <= this.contextData.cArrPos && this.contextData.duplicate();
      var i = this.contextData.saved[this.contextData.cArrPos];

      for (e = 0; e < 16; e += 1) {
        i[e] = r[e];
      }

      this.contextData.savedOp[this.contextData.cArrPos] = this.contextData.cO, this.contextData.cArrPos += 1;
    } else this.canvasContext.save();
  }, CanvasRenderer.prototype.restore = function (t) {
    if (this.renderConfig.clearCanvas) {
      t && (this.canvasContext.restore(), this.globalData.blendMode = "source-over"), this.contextData.cArrPos -= 1;
      var e,
          r = this.contextData.saved[this.contextData.cArrPos],
          i = this.contextData.cTr.props;

      for (e = 0; e < 16; e += 1) {
        i[e] = r[e];
      }

      this.canvasContext.setTransform(r[0], r[1], r[4], r[5], r[12], r[13]), r = this.contextData.savedOp[this.contextData.cArrPos], this.contextData.cO = r, this.globalData.currentGlobalAlpha !== r && (this.canvasContext.globalAlpha = r, this.globalData.currentGlobalAlpha = r);
    } else this.canvasContext.restore();
  }, CanvasRenderer.prototype.configAnimation = function (t) {
    if (this.animationItem.wrapper) {
      this.animationItem.container = createTag("canvas");
      var e = this.animationItem.container.style;
      e.width = "100%", e.height = "100%";
      var r = "0px 0px 0px";
      e.transformOrigin = r, e.mozTransformOrigin = r, e.webkitTransformOrigin = r, e["-webkit-transform"] = r, this.animationItem.wrapper.appendChild(this.animationItem.container), this.canvasContext = this.animationItem.container.getContext("2d"), this.renderConfig.className && this.animationItem.container.setAttribute("class", this.renderConfig.className), this.renderConfig.id && this.animationItem.container.setAttribute("id", this.renderConfig.id);
    } else this.canvasContext = this.renderConfig.context;

    this.data = t, this.layers = t.layers, this.transformCanvas = {
      w: t.w,
      h: t.h,
      sx: 0,
      sy: 0,
      tx: 0,
      ty: 0
    }, this.setupGlobalData(t, document.body), this.globalData.canvasContext = this.canvasContext, (this.globalData.renderer = this).globalData.isDashed = !1, this.globalData.progressiveLoad = this.renderConfig.progressiveLoad, this.globalData.transformCanvas = this.transformCanvas, this.elements = createSizedArray(t.layers.length), this.updateContainerSize();
  }, CanvasRenderer.prototype.updateContainerSize = function () {
    var t, e, r, i;

    if (this.reset(), this.animationItem.wrapper && this.animationItem.container ? (t = this.animationItem.wrapper.offsetWidth, e = this.animationItem.wrapper.offsetHeight, this.animationItem.container.setAttribute("width", t * this.renderConfig.dpr), this.animationItem.container.setAttribute("height", e * this.renderConfig.dpr)) : (t = this.canvasContext.canvas.width * this.renderConfig.dpr, e = this.canvasContext.canvas.height * this.renderConfig.dpr), -1 !== this.renderConfig.preserveAspectRatio.indexOf("meet") || -1 !== this.renderConfig.preserveAspectRatio.indexOf("slice")) {
      var s = this.renderConfig.preserveAspectRatio.split(" "),
          a = s[1] || "meet",
          n = s[0] || "xMidYMid",
          o = n.substr(0, 4),
          h = n.substr(4);
      r = t / e, i = this.transformCanvas.w / this.transformCanvas.h, this.transformCanvas.sy = r < i && "meet" === a || i < r && "slice" === a ? (this.transformCanvas.sx = t / (this.transformCanvas.w / this.renderConfig.dpr), t / (this.transformCanvas.w / this.renderConfig.dpr)) : (this.transformCanvas.sx = e / (this.transformCanvas.h / this.renderConfig.dpr), e / (this.transformCanvas.h / this.renderConfig.dpr)), this.transformCanvas.tx = "xMid" === o && (i < r && "meet" === a || r < i && "slice" === a) ? (t - this.transformCanvas.w * (e / this.transformCanvas.h)) / 2 * this.renderConfig.dpr : "xMax" === o && (i < r && "meet" === a || r < i && "slice" === a) ? (t - this.transformCanvas.w * (e / this.transformCanvas.h)) * this.renderConfig.dpr : 0, this.transformCanvas.ty = "YMid" === h && (r < i && "meet" === a || i < r && "slice" === a) ? (e - this.transformCanvas.h * (t / this.transformCanvas.w)) / 2 * this.renderConfig.dpr : "YMax" === h && (r < i && "meet" === a || i < r && "slice" === a) ? (e - this.transformCanvas.h * (t / this.transformCanvas.w)) * this.renderConfig.dpr : 0;
    } else "none" === this.renderConfig.preserveAspectRatio ? (this.transformCanvas.sx = t / (this.transformCanvas.w / this.renderConfig.dpr), this.transformCanvas.sy = e / (this.transformCanvas.h / this.renderConfig.dpr)) : (this.transformCanvas.sx = this.renderConfig.dpr, this.transformCanvas.sy = this.renderConfig.dpr), this.transformCanvas.tx = 0, this.transformCanvas.ty = 0;

    this.transformCanvas.props = [this.transformCanvas.sx, 0, 0, 0, 0, this.transformCanvas.sy, 0, 0, 0, 0, 1, 0, this.transformCanvas.tx, this.transformCanvas.ty, 0, 1], this.ctxTransform(this.transformCanvas.props), this.canvasContext.beginPath(), this.canvasContext.rect(0, 0, this.transformCanvas.w, this.transformCanvas.h), this.canvasContext.closePath(), this.canvasContext.clip(), this.renderFrame(this.renderedFrame, !0);
  }, CanvasRenderer.prototype.destroy = function () {
    var t;

    for (this.renderConfig.clearCanvas && this.animationItem.wrapper && (this.animationItem.wrapper.innerText = ""), t = (this.layers ? this.layers.length : 0) - 1; 0 <= t; t -= 1) {
      this.elements[t] && this.elements[t].destroy();
    }

    this.elements.length = 0, this.globalData.canvasContext = null, this.animationItem.container = null, this.destroyed = !0;
  }, CanvasRenderer.prototype.renderFrame = function (t, e) {
    if ((this.renderedFrame !== t || !0 !== this.renderConfig.clearCanvas || e) && !this.destroyed && -1 !== t) {
      var r;
      this.renderedFrame = t, this.globalData.frameNum = t - this.animationItem._isFirstFrame, this.globalData.frameId += 1, this.globalData._mdf = !this.renderConfig.clearCanvas || e, this.globalData.projectInterface.currentFrame = t;
      var i = this.layers.length;

      for (this.completeLayers || this.checkLayers(t), r = 0; r < i; r += 1) {
        (this.completeLayers || this.elements[r]) && this.elements[r].prepareFrame(t - this.layers[r].st);
      }

      if (this.globalData._mdf) {
        for (!0 === this.renderConfig.clearCanvas ? this.canvasContext.clearRect(0, 0, this.transformCanvas.w, this.transformCanvas.h) : this.save(), r = i - 1; 0 <= r; r -= 1) {
          (this.completeLayers || this.elements[r]) && this.elements[r].renderFrame();
        }

        !0 !== this.renderConfig.clearCanvas && this.restore();
      }
    }
  }, CanvasRenderer.prototype.buildItem = function (t) {
    var e = this.elements;

    if (!e[t] && 99 !== this.layers[t].ty) {
      var r = this.createItem(this.layers[t], this, this.globalData);
      (e[t] = r).initExpressions();
    }
  }, CanvasRenderer.prototype.checkPendingElements = function () {
    for (; this.pendingElements.length;) {
      this.pendingElements.pop().checkParenting();
    }
  }, CanvasRenderer.prototype.hide = function () {
    this.animationItem.container.style.display = "none";
  }, CanvasRenderer.prototype.show = function () {
    this.animationItem.container.style.display = "block";
  }, extendPrototype([BaseRenderer], HybridRenderer), HybridRenderer.prototype.buildItem = SVGRenderer.prototype.buildItem, HybridRenderer.prototype.checkPendingElements = function () {
    for (; this.pendingElements.length;) {
      this.pendingElements.pop().checkParenting();
    }
  }, HybridRenderer.prototype.appendElementInPos = function (t, e) {
    var r = t.getBaseElement();

    if (r) {
      var i = this.layers[e];
      if (i.ddd && this.supports3d) this.addTo3dContainer(r, e);else if (this.threeDElements) this.addTo3dContainer(r, e);else {
        for (var s, a, n = 0; n < e;) {
          this.elements[n] && !0 !== this.elements[n] && this.elements[n].getBaseElement && (a = this.elements[n], s = (this.layers[n].ddd ? this.getThreeDContainerByPos(n) : a.getBaseElement()) || s), n += 1;
        }

        s ? i.ddd && this.supports3d || this.layerElement.insertBefore(r, s) : i.ddd && this.supports3d || this.layerElement.appendChild(r);
      }
    }
  }, HybridRenderer.prototype.createShape = function (t) {
    return this.supports3d ? new HShapeElement(t, this.globalData, this) : new SVGShapeElement(t, this.globalData, this);
  }, HybridRenderer.prototype.createText = function (t) {
    return this.supports3d ? new HTextElement(t, this.globalData, this) : new SVGTextLottieElement(t, this.globalData, this);
  }, HybridRenderer.prototype.createCamera = function (t) {
    return this.camera = new HCameraElement(t, this.globalData, this), this.camera;
  }, HybridRenderer.prototype.createImage = function (t) {
    return this.supports3d ? new HImageElement(t, this.globalData, this) : new IImageElement(t, this.globalData, this);
  }, HybridRenderer.prototype.createComp = function (t) {
    return this.supports3d ? new HCompElement(t, this.globalData, this) : new SVGCompElement(t, this.globalData, this);
  }, HybridRenderer.prototype.createSolid = function (t) {
    return this.supports3d ? new HSolidElement(t, this.globalData, this) : new ISolidElement(t, this.globalData, this);
  }, HybridRenderer.prototype.createNull = SVGRenderer.prototype.createNull, HybridRenderer.prototype.getThreeDContainerByPos = function (t) {
    for (var e = 0, r = this.threeDElements.length; e < r;) {
      if (this.threeDElements[e].startPos <= t && this.threeDElements[e].endPos >= t) return this.threeDElements[e].perspectiveElem;
      e += 1;
    }

    return null;
  }, HybridRenderer.prototype.createThreeDContainer = function (t, e) {
    var r,
        i,
        s = createTag("div");
    styleDiv(s);
    var a = createTag("div");

    if (styleDiv(a), "3d" === e) {
      (r = s.style).width = this.globalData.compSize.w + "px", r.height = this.globalData.compSize.h + "px";
      var n = "50% 50%";
      r.webkitTransformOrigin = n, r.mozTransformOrigin = n, r.transformOrigin = n;
      var o = "matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1)";
      (i = a.style).transform = o, i.webkitTransform = o;
    }

    s.appendChild(a);
    var h = {
      container: a,
      perspectiveElem: s,
      startPos: t,
      endPos: t,
      type: e
    };
    return this.threeDElements.push(h), h;
  }, HybridRenderer.prototype.build3dContainers = function () {
    var t,
        e,
        r = this.layers.length,
        i = "";

    for (t = 0; t < r; t += 1) {
      this.layers[t].ddd && 3 !== this.layers[t].ty ? "3d" !== i && (i = "3d", e = this.createThreeDContainer(t, "3d")) : "2d" !== i && (i = "2d", e = this.createThreeDContainer(t, "2d")), e.endPos = Math.max(e.endPos, t);
    }

    for (t = (r = this.threeDElements.length) - 1; 0 <= t; t -= 1) {
      this.resizerElem.appendChild(this.threeDElements[t].perspectiveElem);
    }
  }, HybridRenderer.prototype.addTo3dContainer = function (t, e) {
    for (var r = 0, i = this.threeDElements.length; r < i;) {
      if (e <= this.threeDElements[r].endPos) {
        for (var s, a = this.threeDElements[r].startPos; a < e;) {
          this.elements[a] && this.elements[a].getBaseElement && (s = this.elements[a].getBaseElement()), a += 1;
        }

        s ? this.threeDElements[r].container.insertBefore(t, s) : this.threeDElements[r].container.appendChild(t);
        break;
      }

      r += 1;
    }
  }, HybridRenderer.prototype.configAnimation = function (t) {
    var e = createTag("div"),
        r = this.animationItem.wrapper,
        i = e.style;
    i.width = t.w + "px", i.height = t.h + "px", styleDiv(this.resizerElem = e), i.transformStyle = "flat", i.mozTransformStyle = "flat", i.webkitTransformStyle = "flat", this.renderConfig.className && e.setAttribute("class", this.renderConfig.className), r.appendChild(e), i.overflow = "hidden";
    var s = createNS("svg");
    s.setAttribute("width", "1"), s.setAttribute("height", "1"), styleDiv(s), this.resizerElem.appendChild(s);
    var a = createNS("defs");
    s.appendChild(a), this.data = t, this.setupGlobalData(t, s), this.globalData.defs = a, this.layers = t.layers, this.layerElement = this.resizerElem, this.build3dContainers(), this.updateContainerSize();
  }, HybridRenderer.prototype.destroy = function () {
    var t;
    this.animationItem.wrapper && (this.animationItem.wrapper.innerText = ""), this.animationItem.container = null, this.globalData.defs = null;
    var e = this.layers ? this.layers.length : 0;

    for (t = 0; t < e; t += 1) {
      this.elements[t].destroy();
    }

    this.elements.length = 0, this.destroyed = !0, this.animationItem = null;
  }, HybridRenderer.prototype.updateContainerSize = function () {
    var t,
        e,
        r,
        i,
        s = this.animationItem.wrapper.offsetWidth,
        a = this.animationItem.wrapper.offsetHeight;
    i = s / a < this.globalData.compSize.w / this.globalData.compSize.h ? (t = s / this.globalData.compSize.w, e = s / this.globalData.compSize.w, r = 0, (a - this.globalData.compSize.h * (s / this.globalData.compSize.w)) / 2) : (t = a / this.globalData.compSize.h, e = a / this.globalData.compSize.h, r = (s - this.globalData.compSize.w * (a / this.globalData.compSize.h)) / 2, 0);
    var n = this.resizerElem.style;
    n.webkitTransform = "matrix3d(" + t + ",0,0,0,0," + e + ",0,0,0,0,1,0," + r + "," + i + ",0,1)", n.transform = n.webkitTransform;
  }, HybridRenderer.prototype.renderFrame = SVGRenderer.prototype.renderFrame, HybridRenderer.prototype.hide = function () {
    this.resizerElem.style.display = "none";
  }, HybridRenderer.prototype.show = function () {
    this.resizerElem.style.display = "block";
  }, HybridRenderer.prototype.initItems = function () {
    if (this.buildAllItems(), this.camera) this.camera.setup();else {
      var t,
          e = this.globalData.compSize.w,
          r = this.globalData.compSize.h,
          i = this.threeDElements.length;

      for (t = 0; t < i; t += 1) {
        var s = this.threeDElements[t].perspectiveElem.style;
        s.webkitPerspective = Math.sqrt(Math.pow(e, 2) + Math.pow(r, 2)) + "px", s.perspective = s.webkitPerspective;
      }
    }
  }, HybridRenderer.prototype.searchExtraCompositions = function (t) {
    var e,
        r = t.length,
        i = createTag("div");

    for (e = 0; e < r; e += 1) {
      if (t[e].xt) {
        var s = this.createComp(t[e], i, this.globalData.comp, null);
        s.initExpressions(), this.globalData.projectInterface.registerComposition(s);
      }
    }
  }, MaskElement.prototype.getMaskProperty = function (t) {
    return this.viewData[t].prop;
  }, MaskElement.prototype.renderFrame = function (t) {
    var e,
        r = this.element.finalTransform.mat,
        i = this.masksProperties.length;

    for (e = 0; e < i; e += 1) {
      if ((this.viewData[e].prop._mdf || t) && this.drawPath(this.masksProperties[e], this.viewData[e].prop.v, this.viewData[e]), (this.viewData[e].op._mdf || t) && this.viewData[e].elem.setAttribute("fill-opacity", this.viewData[e].op.v), "n" !== this.masksProperties[e].mode && (this.viewData[e].invRect && (this.element.finalTransform.mProp._mdf || t) && this.viewData[e].invRect.setAttribute("transform", r.getInverseMatrix().to2dCSS()), this.storedData[e].x && (this.storedData[e].x._mdf || t))) {
        var s = this.storedData[e].expan;
        this.storedData[e].x.v < 0 ? ("erode" !== this.storedData[e].lastOperator && (this.storedData[e].lastOperator = "erode", this.storedData[e].elem.setAttribute("filter", "url(" + locationHref + "#" + this.storedData[e].filterId + ")")), s.setAttribute("radius", -this.storedData[e].x.v)) : ("dilate" !== this.storedData[e].lastOperator && (this.storedData[e].lastOperator = "dilate", this.storedData[e].elem.setAttribute("filter", null)), this.storedData[e].elem.setAttribute("stroke-width", 2 * this.storedData[e].x.v));
      }
    }
  }, MaskElement.prototype.getMaskelement = function () {
    return this.maskElement;
  }, MaskElement.prototype.createLayerSolidPath = function () {
    var t = "M0,0 ";
    return t += " h" + this.globalData.compSize.w, t += " v" + this.globalData.compSize.h, t += " h-" + this.globalData.compSize.w, t += " v-" + this.globalData.compSize.h + " ";
  }, MaskElement.prototype.drawPath = function (t, e, r) {
    var i,
        s,
        a = " M" + e.v[0][0] + "," + e.v[0][1];

    for (s = e._length, i = 1; i < s; i += 1) {
      a += " C" + e.o[i - 1][0] + "," + e.o[i - 1][1] + " " + e.i[i][0] + "," + e.i[i][1] + " " + e.v[i][0] + "," + e.v[i][1];
    }

    if (e.c && 1 < s && (a += " C" + e.o[i - 1][0] + "," + e.o[i - 1][1] + " " + e.i[0][0] + "," + e.i[0][1] + " " + e.v[0][0] + "," + e.v[0][1]), r.lastPath !== a) {
      var n = "";
      r.elem && (e.c && (n = t.inv ? this.solidPath + a : a), r.elem.setAttribute("d", n)), r.lastPath = a;
    }
  }, MaskElement.prototype.destroy = function () {
    this.element = null, this.globalData = null, this.maskElement = null, this.data = null, this.masksProperties = null;
  }, HierarchyElement.prototype = {
    initHierarchy: function initHierarchy() {
      this.hierarchy = [], this._isParent = !1, this.checkParenting();
    },
    setHierarchy: function setHierarchy(t) {
      this.hierarchy = t;
    },
    setAsParent: function setAsParent() {
      this._isParent = !0;
    },
    checkParenting: function checkParenting() {
      void 0 !== this.data.parent && this.comp.buildElementParenting(this, this.data.parent, []);
    }
  }, FrameElement.prototype = {
    initFrame: function initFrame() {
      this._isFirstFrame = !1, this.dynamicProperties = [], this._mdf = !1;
    },
    prepareProperties: function prepareProperties(t, e) {
      var r,
          i = this.dynamicProperties.length;

      for (r = 0; r < i; r += 1) {
        (e || this._isParent && "transform" === this.dynamicProperties[r].propType) && (this.dynamicProperties[r].getValue(), this.dynamicProperties[r]._mdf && (this.globalData._mdf = !0, this._mdf = !0));
      }
    },
    addDynamicProperty: function addDynamicProperty(t) {
      -1 === this.dynamicProperties.indexOf(t) && this.dynamicProperties.push(t);
    }
  }, TransformElement.prototype = {
    initTransform: function initTransform() {
      this.finalTransform = {
        mProp: this.data.ks ? TransformPropertyFactory.getTransformProperty(this, this.data.ks, this) : {
          o: 0
        },
        _matMdf: !1,
        _opMdf: !1,
        mat: new Matrix()
      }, this.data.ao && (this.finalTransform.mProp.autoOriented = !0), this.data.ty;
    },
    renderTransform: function renderTransform() {
      if (this.finalTransform._opMdf = this.finalTransform.mProp.o._mdf || this._isFirstFrame, this.finalTransform._matMdf = this.finalTransform.mProp._mdf || this._isFirstFrame, this.hierarchy) {
        var t,
            e = this.finalTransform.mat,
            r = 0,
            i = this.hierarchy.length;
        if (!this.finalTransform._matMdf) for (; r < i;) {
          if (this.hierarchy[r].finalTransform.mProp._mdf) {
            this.finalTransform._matMdf = !0;
            break;
          }

          r += 1;
        }
        if (this.finalTransform._matMdf) for (t = this.finalTransform.mProp.v.props, e.cloneFromProps(t), r = 0; r < i; r += 1) {
          t = this.hierarchy[r].finalTransform.mProp.v.props, e.transform(t[0], t[1], t[2], t[3], t[4], t[5], t[6], t[7], t[8], t[9], t[10], t[11], t[12], t[13], t[14], t[15]);
        }
      }
    },
    globalToLocal: function globalToLocal(t) {
      var e = [];
      e.push(this.finalTransform);

      for (var r, i = !0, s = this.comp; i;) {
        s.finalTransform ? (s.data.hasMask && e.splice(0, 0, s.finalTransform), s = s.comp) : i = !1;
      }

      var a,
          n = e.length;

      for (r = 0; r < n; r += 1) {
        a = e[r].mat.applyToPointArray(0, 0, 0), t = [t[0] - a[0], t[1] - a[1], 0];
      }

      return t;
    },
    mHelper: new Matrix()
  }, RenderableElement.prototype = {
    initRenderable: function initRenderable() {
      this.isInRange = !1, this.hidden = !1, this.isTransparent = !1, this.renderableComponents = [];
    },
    addRenderableComponent: function addRenderableComponent(t) {
      -1 === this.renderableComponents.indexOf(t) && this.renderableComponents.push(t);
    },
    removeRenderableComponent: function removeRenderableComponent(t) {
      -1 !== this.renderableComponents.indexOf(t) && this.renderableComponents.splice(this.renderableComponents.indexOf(t), 1);
    },
    prepareRenderableFrame: function prepareRenderableFrame(t) {
      this.checkLayerLimits(t);
    },
    checkTransparency: function checkTransparency() {
      this.finalTransform.mProp.o.v <= 0 ? !this.isTransparent && this.globalData.renderConfig.hideOnTransparent && (this.isTransparent = !0, this.hide()) : this.isTransparent && (this.isTransparent = !1, this.show());
    },
    checkLayerLimits: function checkLayerLimits(t) {
      this.data.ip - this.data.st <= t && this.data.op - this.data.st > t ? !0 !== this.isInRange && (this.globalData._mdf = !0, this._mdf = !0, this.isInRange = !0, this.show()) : !1 !== this.isInRange && (this.globalData._mdf = !0, this.isInRange = !1, this.hide());
    },
    renderRenderable: function renderRenderable() {
      var t,
          e = this.renderableComponents.length;

      for (t = 0; t < e; t += 1) {
        this.renderableComponents[t].renderFrame(this._isFirstFrame);
      }
    },
    sourceRectAtTime: function sourceRectAtTime() {
      return {
        top: 0,
        left: 0,
        width: 100,
        height: 100
      };
    },
    getLayerSize: function getLayerSize() {
      return 5 === this.data.ty ? {
        w: this.data.textData.width,
        h: this.data.textData.height
      } : {
        w: this.data.width,
        h: this.data.height
      };
    }
  }, extendPrototype([RenderableElement, createProxyFunction({
    initElement: function initElement(t, e, r) {
      this.initFrame(), this.initBaseData(t, e, r), this.initTransform(t, e, r), this.initHierarchy(), this.initRenderable(), this.initRendererElement(), this.createContainerElements(), this.createRenderableComponents(), this.createContent(), this.hide();
    },
    hide: function hide() {
      this.hidden || this.isInRange && !this.isTransparent || ((this.baseElement || this.layerElement).style.display = "none", this.hidden = !0);
    },
    show: function show() {
      this.isInRange && !this.isTransparent && (this.data.hd || ((this.baseElement || this.layerElement).style.display = "block"), this.hidden = !1, this._isFirstFrame = !0);
    },
    renderFrame: function renderFrame() {
      this.data.hd || this.hidden || (this.renderTransform(), this.renderRenderable(), this.renderElement(), this.renderInnerContent(), this._isFirstFrame && (this._isFirstFrame = !1));
    },
    renderInnerContent: function renderInnerContent() {},
    prepareFrame: function prepareFrame(t) {
      this._mdf = !1, this.prepareRenderableFrame(t), this.prepareProperties(t, this.isInRange), this.checkTransparency();
    },
    destroy: function destroy() {
      this.innerElem = null, this.destroyBaseElement();
    }
  })], RenderableDOMElement), SVGStyleData.prototype.reset = function () {
    this.d = "", this._mdf = !1;
  }, SVGShapeData.prototype.setAsAnimated = function () {
    this._isAnimated = !0;
  }, extendPrototype([DynamicPropertyContainer], SVGStrokeStyleData), extendPrototype([DynamicPropertyContainer], SVGFillStyleData), SVGGradientFillStyleData.prototype.initGradientData = function (t, e, r) {
    this.o = PropertyFactory.getProp(t, e.o, 0, .01, this), this.s = PropertyFactory.getProp(t, e.s, 1, null, this), this.e = PropertyFactory.getProp(t, e.e, 1, null, this), this.h = PropertyFactory.getProp(t, e.h || {
      k: 0
    }, 0, .01, this), this.a = PropertyFactory.getProp(t, e.a || {
      k: 0
    }, 0, degToRads, this), this.g = new GradientProperty(t, e.g, this), this.style = r, this.stops = [], this.setGradientData(r.pElem, e), this.setGradientOpacity(e, r), this._isAnimated = !!this._isAnimated;
  }, SVGGradientFillStyleData.prototype.setGradientData = function (t, e) {
    var r = createElementID(),
        i = createNS(1 === e.t ? "linearGradient" : "radialGradient");
    i.setAttribute("id", r), i.setAttribute("spreadMethod", "pad"), i.setAttribute("gradientUnits", "userSpaceOnUse");
    var s,
        a,
        n,
        o = [];

    for (n = 4 * e.g.p, a = 0; a < n; a += 4) {
      s = createNS("stop"), i.appendChild(s), o.push(s);
    }

    t.setAttribute("gf" === e.ty ? "fill" : "stroke", "url(" + locationHref + "#" + r + ")"), this.gf = i, this.cst = o;
  }, SVGGradientFillStyleData.prototype.setGradientOpacity = function (t, e) {
    if (this.g._hasOpacity && !this.g._collapsable) {
      var r,
          i,
          s,
          a = createNS("mask"),
          n = createNS("path");
      a.appendChild(n);
      var o = createElementID(),
          h = createElementID();
      a.setAttribute("id", h);
      var l = createNS(1 === t.t ? "linearGradient" : "radialGradient");
      l.setAttribute("id", o), l.setAttribute("spreadMethod", "pad"), l.setAttribute("gradientUnits", "userSpaceOnUse"), s = t.g.k.k[0].s ? t.g.k.k[0].s.length : t.g.k.k.length;
      var p = this.stops;

      for (i = 4 * t.g.p; i < s; i += 2) {
        (r = createNS("stop")).setAttribute("stop-color", "rgb(255,255,255)"), l.appendChild(r), p.push(r);
      }

      n.setAttribute("gf" === t.ty ? "fill" : "stroke", "url(" + locationHref + "#" + o + ")"), "gs" === t.ty && (n.setAttribute("stroke-linecap", lineCapEnum[t.lc || 2]), n.setAttribute("stroke-linejoin", lineJoinEnum[t.lj || 2]), 1 === t.lj && n.setAttribute("stroke-miterlimit", t.ml)), this.of = l, this.ms = a, this.ost = p, this.maskId = h, e.msElem = n;
    }
  }, extendPrototype([DynamicPropertyContainer], SVGGradientFillStyleData), extendPrototype([SVGGradientFillStyleData, DynamicPropertyContainer], SVGGradientStrokeStyleData);

  var SVGElementsRenderer = function () {
    var y = new Matrix(),
        g = new Matrix();

    function e(t, e, r) {
      (r || e.transform.op._mdf) && e.transform.container.setAttribute("opacity", e.transform.op.v), (r || e.transform.mProps._mdf) && e.transform.container.setAttribute("transform", e.transform.mProps.v.to2dCSS());
    }

    function r(t, e, r) {
      var i,
          s,
          a,
          n,
          o,
          h,
          l,
          p,
          m,
          f,
          c,
          d = e.styles.length,
          u = e.lvl;

      for (h = 0; h < d; h += 1) {
        if (n = e.sh._mdf || r, e.styles[h].lvl < u) {
          for (p = g.reset(), f = u - e.styles[h].lvl, c = e.transformers.length - 1; !n && 0 < f;) {
            n = e.transformers[c].mProps._mdf || n, f -= 1, c -= 1;
          }

          if (n) for (f = u - e.styles[h].lvl, c = e.transformers.length - 1; 0 < f;) {
            m = e.transformers[c].mProps.v.props, p.transform(m[0], m[1], m[2], m[3], m[4], m[5], m[6], m[7], m[8], m[9], m[10], m[11], m[12], m[13], m[14], m[15]), f -= 1, c -= 1;
          }
        } else p = y;

        if (s = (l = e.sh.paths)._length, n) {
          for (a = "", i = 0; i < s; i += 1) {
            (o = l.shapes[i]) && o._length && (a += buildShapeString(o, o._length, o.c, p));
          }

          e.caches[h] = a;
        } else a = e.caches[h];

        e.styles[h].d += !0 === t.hd ? "" : a, e.styles[h]._mdf = n || e.styles[h]._mdf;
      }
    }

    function i(t, e, r) {
      var i = e.style;
      (e.c._mdf || r) && i.pElem.setAttribute("fill", "rgb(" + bmFloor(e.c.v[0]) + "," + bmFloor(e.c.v[1]) + "," + bmFloor(e.c.v[2]) + ")"), (e.o._mdf || r) && i.pElem.setAttribute("fill-opacity", e.o.v);
    }

    function s(t, e, r) {
      a(t, e, r), n(t, e, r);
    }

    function a(t, e, r) {
      var i,
          s,
          a,
          n,
          o,
          h = e.gf,
          l = e.g._hasOpacity,
          p = e.s.v,
          m = e.e.v;

      if (e.o._mdf || r) {
        var f = "gf" === t.ty ? "fill-opacity" : "stroke-opacity";
        e.style.pElem.setAttribute(f, e.o.v);
      }

      if (e.s._mdf || r) {
        var c = 1 === t.t ? "x1" : "cx",
            d = "x1" === c ? "y1" : "cy";
        h.setAttribute(c, p[0]), h.setAttribute(d, p[1]), l && !e.g._collapsable && (e.of.setAttribute(c, p[0]), e.of.setAttribute(d, p[1]));
      }

      if (e.g._cmdf || r) {
        i = e.cst;
        var u = e.g.c;

        for (a = i.length, s = 0; s < a; s += 1) {
          (n = i[s]).setAttribute("offset", u[4 * s] + "%"), n.setAttribute("stop-color", "rgb(" + u[4 * s + 1] + "," + u[4 * s + 2] + "," + u[4 * s + 3] + ")");
        }
      }

      if (l && (e.g._omdf || r)) {
        var y = e.g.o;

        for (a = (i = e.g._collapsable ? e.cst : e.ost).length, s = 0; s < a; s += 1) {
          n = i[s], e.g._collapsable || n.setAttribute("offset", y[2 * s] + "%"), n.setAttribute("stop-opacity", y[2 * s + 1]);
        }
      }

      if (1 === t.t) (e.e._mdf || r) && (h.setAttribute("x2", m[0]), h.setAttribute("y2", m[1]), l && !e.g._collapsable && (e.of.setAttribute("x2", m[0]), e.of.setAttribute("y2", m[1])));else if ((e.s._mdf || e.e._mdf || r) && (o = Math.sqrt(Math.pow(p[0] - m[0], 2) + Math.pow(p[1] - m[1], 2)), h.setAttribute("r", o), l && !e.g._collapsable && e.of.setAttribute("r", o)), e.e._mdf || e.h._mdf || e.a._mdf || r) {
        o || (o = Math.sqrt(Math.pow(p[0] - m[0], 2) + Math.pow(p[1] - m[1], 2)));
        var g = Math.atan2(m[1] - p[1], m[0] - p[0]),
            v = e.h.v;
        1 <= v ? v = .99 : v <= -1 && (v = -.99);
        var b = o * v,
            P = Math.cos(g + e.a.v) * b + p[0],
            E = Math.sin(g + e.a.v) * b + p[1];
        h.setAttribute("fx", P), h.setAttribute("fy", E), l && !e.g._collapsable && (e.of.setAttribute("fx", P), e.of.setAttribute("fy", E));
      }
    }

    function n(t, e, r) {
      var i = e.style,
          s = e.d;
      s && (s._mdf || r) && s.dashStr && (i.pElem.setAttribute("stroke-dasharray", s.dashStr), i.pElem.setAttribute("stroke-dashoffset", s.dashoffset[0])), e.c && (e.c._mdf || r) && i.pElem.setAttribute("stroke", "rgb(" + bmFloor(e.c.v[0]) + "," + bmFloor(e.c.v[1]) + "," + bmFloor(e.c.v[2]) + ")"), (e.o._mdf || r) && i.pElem.setAttribute("stroke-opacity", e.o.v), (e.w._mdf || r) && (i.pElem.setAttribute("stroke-width", e.w.v), i.msElem && i.msElem.setAttribute("stroke-width", e.w.v));
    }

    return {
      createRenderFunction: function createRenderFunction(t) {
        switch (t.ty) {
          case "fl":
            return i;

          case "gf":
            return a;

          case "gs":
            return s;

          case "st":
            return n;

          case "sh":
          case "el":
          case "rc":
          case "sr":
            return r;

          case "tr":
            return e;

          default:
            return null;
        }
      }
    };
  }();

  function ShapeTransformManager() {
    this.sequences = {}, this.sequenceList = [], this.transform_key_count = 0;
  }

  function CVShapeData(t, e, r, i) {
    this.styledShapes = [], this.tr = [0, 0, 0, 0, 0, 0];
    var s,
        a = 4;
    "rc" === e.ty ? a = 5 : "el" === e.ty ? a = 6 : "sr" === e.ty && (a = 7), this.sh = ShapePropertyFactory.getShapeProp(t, e, a, t);
    var n,
        o = r.length;

    for (s = 0; s < o; s += 1) {
      r[s].closed || (n = {
        transforms: i.addTransformSequence(r[s].transforms),
        trNodes: []
      }, this.styledShapes.push(n), r[s].elements.push(n));
    }
  }

  function BaseElement() {}

  function NullElement(t, e, r) {
    this.initFrame(), this.initBaseData(t, e, r), this.initFrame(), this.initTransform(t, e, r), this.initHierarchy();
  }

  function SVGBaseElement() {}

  function IShapeElement() {}

  function ITextElement() {}

  function ICompElement() {}

  function IImageElement(t, e, r) {
    this.assetData = e.getAssetData(t.refId), this.initElement(t, e, r), this.sourceRect = {
      top: 0,
      left: 0,
      width: this.assetData.w,
      height: this.assetData.h
    };
  }

  function ISolidElement(t, e, r) {
    this.initElement(t, e, r);
  }

  function AudioElement(t, e, r) {
    this.initFrame(), this.initRenderable(), this.assetData = e.getAssetData(t.refId), this.initBaseData(t, e, r), this._isPlaying = !1, this._canPlay = !1;
    var i = this.globalData.getAssetsPath(this.assetData);
    this.audio = this.globalData.audioController.createAudio(i), this._currentTime = 0, this.globalData.audioController.addAudio(this), this.tm = t.tm ? PropertyFactory.getProp(this, t.tm, 0, e.frameRate, this) : {
      _placeholder: !0
    };
  }

  function FootageElement(t, e, r) {
    this.initFrame(), this.initRenderable(), this.assetData = e.getAssetData(t.refId), this.footageData = e.imageLoader.getAsset(this.assetData), this.initBaseData(t, e, r);
  }

  function SVGCompElement(t, e, r) {
    this.layers = t.layers, this.supports3d = !0, this.completeLayers = !1, this.pendingElements = [], this.elements = this.layers ? createSizedArray(this.layers.length) : [], this.initElement(t, e, r), this.tm = t.tm ? PropertyFactory.getProp(this, t.tm, 0, e.frameRate, this) : {
      _placeholder: !0
    };
  }

  function SVGTextLottieElement(t, e, r) {
    this.textSpans = [], this.renderType = "svg", this.initElement(t, e, r);
  }

  function SVGShapeElement(t, e, r) {
    this.shapes = [], this.shapesData = t.shapes, this.stylesList = [], this.shapeModifiers = [], this.itemsData = [], this.processedElements = [], this.animatedContents = [], this.initElement(t, e, r), this.prevViewData = [];
  }

  function SVGTintFilter(t, e) {
    this.filterManager = e;
    var r = createNS("feColorMatrix");

    if (r.setAttribute("type", "matrix"), r.setAttribute("color-interpolation-filters", "linearRGB"), r.setAttribute("values", "0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0"), r.setAttribute("result", "f1"), t.appendChild(r), (r = createNS("feColorMatrix")).setAttribute("type", "matrix"), r.setAttribute("color-interpolation-filters", "sRGB"), r.setAttribute("values", "1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0"), r.setAttribute("result", "f2"), t.appendChild(r), this.matrixFilter = r, 100 !== e.effectElements[2].p.v || e.effectElements[2].p.k) {
      var i,
          s = createNS("feMerge");
      t.appendChild(s), (i = createNS("feMergeNode")).setAttribute("in", "SourceGraphic"), s.appendChild(i), (i = createNS("feMergeNode")).setAttribute("in", "f2"), s.appendChild(i);
    }
  }

  function SVGFillFilter(t, e) {
    this.filterManager = e;
    var r = createNS("feColorMatrix");
    r.setAttribute("type", "matrix"), r.setAttribute("color-interpolation-filters", "sRGB"), r.setAttribute("values", "1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0"), t.appendChild(r), this.matrixFilter = r;
  }

  function SVGGaussianBlurEffect(t, e) {
    t.setAttribute("x", "-100%"), t.setAttribute("y", "-100%"), t.setAttribute("width", "300%"), t.setAttribute("height", "300%"), this.filterManager = e;
    var r = createNS("feGaussianBlur");
    t.appendChild(r), this.feGaussianBlur = r;
  }

  function SVGStrokeEffect(t, e) {
    this.initialized = !1, this.filterManager = e, this.elem = t, this.paths = [];
  }

  function SVGTritoneFilter(t, e) {
    this.filterManager = e;
    var r = createNS("feColorMatrix");
    r.setAttribute("type", "matrix"), r.setAttribute("color-interpolation-filters", "linearRGB"), r.setAttribute("values", "0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0"), r.setAttribute("result", "f1"), t.appendChild(r);
    var i = createNS("feComponentTransfer");
    i.setAttribute("color-interpolation-filters", "sRGB"), t.appendChild(i), this.matrixFilter = i;
    var s = createNS("feFuncR");
    s.setAttribute("type", "table"), i.appendChild(s), this.feFuncR = s;
    var a = createNS("feFuncG");
    a.setAttribute("type", "table"), i.appendChild(a), this.feFuncG = a;
    var n = createNS("feFuncB");
    n.setAttribute("type", "table"), i.appendChild(n), this.feFuncB = n;
  }

  function SVGProLevelsFilter(t, e) {
    this.filterManager = e;
    var r = this.filterManager.effectElements,
        i = createNS("feComponentTransfer");
    (r[10].p.k || 0 !== r[10].p.v || r[11].p.k || 1 !== r[11].p.v || r[12].p.k || 1 !== r[12].p.v || r[13].p.k || 0 !== r[13].p.v || r[14].p.k || 1 !== r[14].p.v) && (this.feFuncR = this.createFeFunc("feFuncR", i)), (r[17].p.k || 0 !== r[17].p.v || r[18].p.k || 1 !== r[18].p.v || r[19].p.k || 1 !== r[19].p.v || r[20].p.k || 0 !== r[20].p.v || r[21].p.k || 1 !== r[21].p.v) && (this.feFuncG = this.createFeFunc("feFuncG", i)), (r[24].p.k || 0 !== r[24].p.v || r[25].p.k || 1 !== r[25].p.v || r[26].p.k || 1 !== r[26].p.v || r[27].p.k || 0 !== r[27].p.v || r[28].p.k || 1 !== r[28].p.v) && (this.feFuncB = this.createFeFunc("feFuncB", i)), (r[31].p.k || 0 !== r[31].p.v || r[32].p.k || 1 !== r[32].p.v || r[33].p.k || 1 !== r[33].p.v || r[34].p.k || 0 !== r[34].p.v || r[35].p.k || 1 !== r[35].p.v) && (this.feFuncA = this.createFeFunc("feFuncA", i)), (this.feFuncR || this.feFuncG || this.feFuncB || this.feFuncA) && (i.setAttribute("color-interpolation-filters", "sRGB"), t.appendChild(i), i = createNS("feComponentTransfer")), (r[3].p.k || 0 !== r[3].p.v || r[4].p.k || 1 !== r[4].p.v || r[5].p.k || 1 !== r[5].p.v || r[6].p.k || 0 !== r[6].p.v || r[7].p.k || 1 !== r[7].p.v) && (i.setAttribute("color-interpolation-filters", "sRGB"), t.appendChild(i), this.feFuncRComposed = this.createFeFunc("feFuncR", i), this.feFuncGComposed = this.createFeFunc("feFuncG", i), this.feFuncBComposed = this.createFeFunc("feFuncB", i));
  }

  function SVGDropShadowEffect(t, e) {
    var r = e.container.globalData.renderConfig.filterSize;
    t.setAttribute("x", r.x), t.setAttribute("y", r.y), t.setAttribute("width", r.width), t.setAttribute("height", r.height), this.filterManager = e;
    var i = createNS("feGaussianBlur");
    i.setAttribute("in", "SourceAlpha"), i.setAttribute("result", "drop_shadow_1"), i.setAttribute("stdDeviation", "0"), this.feGaussianBlur = i, t.appendChild(i);
    var s = createNS("feOffset");
    s.setAttribute("dx", "25"), s.setAttribute("dy", "0"), s.setAttribute("in", "drop_shadow_1"), s.setAttribute("result", "drop_shadow_2"), this.feOffset = s, t.appendChild(s);
    var a = createNS("feFlood");
    a.setAttribute("flood-color", "#00ff00"), a.setAttribute("flood-opacity", "1"), a.setAttribute("result", "drop_shadow_3"), this.feFlood = a, t.appendChild(a);
    var n = createNS("feComposite");
    n.setAttribute("in", "drop_shadow_3"), n.setAttribute("in2", "drop_shadow_2"), n.setAttribute("operator", "in"), n.setAttribute("result", "drop_shadow_4"), t.appendChild(n);
    var o,
        h = createNS("feMerge");
    t.appendChild(h), o = createNS("feMergeNode"), h.appendChild(o), (o = createNS("feMergeNode")).setAttribute("in", "SourceGraphic"), this.feMergeNode = o, this.feMerge = h, this.originalNodeAdded = !1, h.appendChild(o);
  }

  ShapeTransformManager.prototype = {
    addTransformSequence: function addTransformSequence(t) {
      var e,
          r = t.length,
          i = "_";

      for (e = 0; e < r; e += 1) {
        i += t[e].transform.key + "_";
      }

      var s = this.sequences[i];
      return s || (s = {
        transforms: [].concat(t),
        finalTransform: new Matrix(),
        _mdf: !1
      }, this.sequences[i] = s, this.sequenceList.push(s)), s;
    },
    processSequence: function processSequence(t, e) {
      for (var r, i = 0, s = t.transforms.length, a = e; i < s && !e;) {
        if (t.transforms[i].transform.mProps._mdf) {
          a = !0;
          break;
        }

        i += 1;
      }

      if (a) for (t.finalTransform.reset(), i = s - 1; 0 <= i; i -= 1) {
        r = t.transforms[i].transform.mProps.v.props, t.finalTransform.transform(r[0], r[1], r[2], r[3], r[4], r[5], r[6], r[7], r[8], r[9], r[10], r[11], r[12], r[13], r[14], r[15]);
      }
      t._mdf = a;
    },
    processSequences: function processSequences(t) {
      var e,
          r = this.sequenceList.length;

      for (e = 0; e < r; e += 1) {
        this.processSequence(this.sequenceList[e], t);
      }
    },
    getNewKey: function getNewKey() {
      return this.transform_key_count += 1, "_" + this.transform_key_count;
    }
  }, CVShapeData.prototype.setAsAnimated = SVGShapeData.prototype.setAsAnimated, BaseElement.prototype = {
    checkMasks: function checkMasks() {
      if (!this.data.hasMask) return !1;

      for (var t = 0, e = this.data.masksProperties.length; t < e;) {
        if ("n" !== this.data.masksProperties[t].mode && !1 !== this.data.masksProperties[t].cl) return !0;
        t += 1;
      }

      return !1;
    },
    initExpressions: function initExpressions() {
      this.layerInterface = LayerExpressionInterface(this), this.data.hasMask && this.maskManager && this.layerInterface.registerMaskInterface(this.maskManager);
      var t = EffectsExpressionInterface.createEffectsInterface(this, this.layerInterface);
      this.layerInterface.registerEffectsInterface(t), 0 === this.data.ty || this.data.xt ? this.compInterface = CompExpressionInterface(this) : 4 === this.data.ty ? (this.layerInterface.shapeInterface = ShapeExpressionInterface(this.shapesData, this.itemsData, this.layerInterface), this.layerInterface.content = this.layerInterface.shapeInterface) : 5 === this.data.ty && (this.layerInterface.textInterface = TextExpressionInterface(this), this.layerInterface.text = this.layerInterface.textInterface);
    },
    setBlendMode: function setBlendMode() {
      var t = getBlendMode(this.data.bm);
      (this.baseElement || this.layerElement).style["mix-blend-mode"] = t;
    },
    initBaseData: function initBaseData(t, e, r) {
      this.globalData = e, this.comp = r, this.data = t, this.layerId = createElementID(), this.data.sr || (this.data.sr = 1), this.effectsManager = new EffectsManager(this.data, this, this.dynamicProperties);
    },
    getType: function getType() {
      return this.type;
    },
    sourceRectAtTime: function sourceRectAtTime() {}
  }, NullElement.prototype.prepareFrame = function (t) {
    this.prepareProperties(t, !0);
  }, NullElement.prototype.renderFrame = function () {}, NullElement.prototype.getBaseElement = function () {
    return null;
  }, NullElement.prototype.destroy = function () {}, NullElement.prototype.sourceRectAtTime = function () {}, NullElement.prototype.hide = function () {}, extendPrototype([BaseElement, TransformElement, HierarchyElement, FrameElement], NullElement), SVGBaseElement.prototype = {
    initRendererElement: function initRendererElement() {
      this.layerElement = createNS("g");
    },
    createContainerElements: function createContainerElements() {
      this.matteElement = createNS("g"), this.transformedElement = this.layerElement, this.maskedElement = this.layerElement, this._sizeChanged = !1;
      var t,
          e,
          r,
          i = null;

      if (this.data.td) {
        if (3 == this.data.td || 1 == this.data.td) {
          var s = createNS("mask");
          s.setAttribute("id", this.layerId), s.setAttribute("mask-type", 3 == this.data.td ? "luminance" : "alpha"), s.appendChild(this.layerElement), i = s, this.globalData.defs.appendChild(s), featureSupport.maskType || 1 != this.data.td || (s.setAttribute("mask-type", "luminance"), t = createElementID(), e = filtersFactory.createFilter(t), this.globalData.defs.appendChild(e), e.appendChild(filtersFactory.createAlphaToLuminanceFilter()), (r = createNS("g")).appendChild(this.layerElement), i = r, s.appendChild(r), r.setAttribute("filter", "url(" + locationHref + "#" + t + ")"));
        } else if (2 == this.data.td) {
          var a = createNS("mask");
          a.setAttribute("id", this.layerId), a.setAttribute("mask-type", "alpha");
          var n = createNS("g");
          a.appendChild(n), t = createElementID(), e = filtersFactory.createFilter(t);
          var o = createNS("feComponentTransfer");
          o.setAttribute("in", "SourceGraphic"), e.appendChild(o);
          var h = createNS("feFuncA");
          h.setAttribute("type", "table"), h.setAttribute("tableValues", "1.0 0.0"), o.appendChild(h), this.globalData.defs.appendChild(e);
          var l = createNS("rect");
          l.setAttribute("width", this.comp.data.w), l.setAttribute("height", this.comp.data.h), l.setAttribute("x", "0"), l.setAttribute("y", "0"), l.setAttribute("fill", "#ffffff"), l.setAttribute("opacity", "0"), n.setAttribute("filter", "url(" + locationHref + "#" + t + ")"), n.appendChild(l), n.appendChild(this.layerElement), i = n, featureSupport.maskType || (a.setAttribute("mask-type", "luminance"), e.appendChild(filtersFactory.createAlphaToLuminanceFilter()), r = createNS("g"), n.appendChild(l), r.appendChild(this.layerElement), i = r, n.appendChild(r)), this.globalData.defs.appendChild(a);
        }
      } else this.data.tt ? (this.matteElement.appendChild(this.layerElement), i = this.matteElement, this.baseElement = this.matteElement) : this.baseElement = this.layerElement;

      if (this.data.ln && this.layerElement.setAttribute("id", this.data.ln), this.data.cl && this.layerElement.setAttribute("class", this.data.cl), 0 === this.data.ty && !this.data.hd) {
        var p = createNS("clipPath"),
            m = createNS("path");
        m.setAttribute("d", "M0,0 L" + this.data.w + ",0 L" + this.data.w + "," + this.data.h + " L0," + this.data.h + "z");
        var f = createElementID();

        if (p.setAttribute("id", f), p.appendChild(m), this.globalData.defs.appendChild(p), this.checkMasks()) {
          var c = createNS("g");
          c.setAttribute("clip-path", "url(" + locationHref + "#" + f + ")"), c.appendChild(this.layerElement), this.transformedElement = c, i ? i.appendChild(this.transformedElement) : this.baseElement = this.transformedElement;
        } else this.layerElement.setAttribute("clip-path", "url(" + locationHref + "#" + f + ")");
      }

      0 !== this.data.bm && this.setBlendMode();
    },
    renderElement: function renderElement() {
      this.finalTransform._matMdf && this.transformedElement.setAttribute("transform", this.finalTransform.mat.to2dCSS()), this.finalTransform._opMdf && this.transformedElement.setAttribute("opacity", this.finalTransform.mProp.o.v);
    },
    destroyBaseElement: function destroyBaseElement() {
      this.layerElement = null, this.matteElement = null, this.maskManager.destroy();
    },
    getBaseElement: function getBaseElement() {
      return this.data.hd ? null : this.baseElement;
    },
    createRenderableComponents: function createRenderableComponents() {
      this.maskManager = new MaskElement(this.data, this, this.globalData), this.renderableEffectsManager = new SVGEffects(this);
    },
    setMatte: function setMatte(t) {
      this.matteElement && this.matteElement.setAttribute("mask", "url(" + locationHref + "#" + t + ")");
    }
  }, IShapeElement.prototype = {
    addShapeToModifiers: function addShapeToModifiers(t) {
      var e,
          r = this.shapeModifiers.length;

      for (e = 0; e < r; e += 1) {
        this.shapeModifiers[e].addShape(t);
      }
    },
    isShapeInAnimatedModifiers: function isShapeInAnimatedModifiers(t) {
      for (var e = this.shapeModifiers.length; 0 < e;) {
        if (this.shapeModifiers[0].isAnimatedWithShape(t)) return !0;
      }

      return !1;
    },
    renderModifiers: function renderModifiers() {
      if (this.shapeModifiers.length) {
        var t,
            e = this.shapes.length;

        for (t = 0; t < e; t += 1) {
          this.shapes[t].sh.reset();
        }

        for (t = (e = this.shapeModifiers.length) - 1; 0 <= t && !this.shapeModifiers[t].processShapes(this._isFirstFrame); t -= 1) {
          ;
        }
      }
    },
    searchProcessedElement: function searchProcessedElement(t) {
      for (var e = this.processedElements, r = 0, i = e.length; r < i;) {
        if (e[r].elem === t) return e[r].pos;
        r += 1;
      }

      return 0;
    },
    addProcessedElement: function addProcessedElement(t, e) {
      for (var r = this.processedElements, i = r.length; i;) {
        if (r[i -= 1].elem === t) return void (r[i].pos = e);
      }

      r.push(new ProcessedElement(t, e));
    },
    prepareFrame: function prepareFrame(t) {
      this.prepareRenderableFrame(t), this.prepareProperties(t, this.isInRange);
    }
  }, ITextElement.prototype.initElement = function (t, e, r) {
    this.lettersChangedFlag = !0, this.initFrame(), this.initBaseData(t, e, r), this.textProperty = new TextProperty(this, t.t, this.dynamicProperties), this.textAnimator = new TextAnimatorProperty(t.t, this.renderType, this), this.initTransform(t, e, r), this.initHierarchy(), this.initRenderable(), this.initRendererElement(), this.createContainerElements(), this.createRenderableComponents(), this.createContent(), this.hide(), this.textAnimator.searchProperties(this.dynamicProperties);
  }, ITextElement.prototype.prepareFrame = function (t) {
    this._mdf = !1, this.prepareRenderableFrame(t), this.prepareProperties(t, this.isInRange), (this.textProperty._mdf || this.textProperty._isFirstFrame) && (this.buildNewText(), this.textProperty._isFirstFrame = !1, this.textProperty._mdf = !1);
  }, ITextElement.prototype.createPathShape = function (t, e) {
    var r,
        i,
        s = e.length,
        a = "";

    for (r = 0; r < s; r += 1) {
      i = e[r].ks.k, a += buildShapeString(i, i.i.length, !0, t);
    }

    return a;
  }, ITextElement.prototype.updateDocumentData = function (t, e) {
    this.textProperty.updateDocumentData(t, e);
  }, ITextElement.prototype.canResizeFont = function (t) {
    this.textProperty.canResizeFont(t);
  }, ITextElement.prototype.setMinimumFontSize = function (t) {
    this.textProperty.setMinimumFontSize(t);
  }, ITextElement.prototype.applyTextPropertiesToMatrix = function (t, e, r, i, s) {
    switch (t.ps && e.translate(t.ps[0], t.ps[1] + t.ascent, 0), e.translate(0, -t.ls, 0), t.j) {
      case 1:
        e.translate(t.justifyOffset + (t.boxWidth - t.lineWidths[r]), 0, 0);
        break;

      case 2:
        e.translate(t.justifyOffset + (t.boxWidth - t.lineWidths[r]) / 2, 0, 0);
    }

    e.translate(i, s, 0);
  }, ITextElement.prototype.buildColor = function (t) {
    return "rgb(" + Math.round(255 * t[0]) + "," + Math.round(255 * t[1]) + "," + Math.round(255 * t[2]) + ")";
  }, ITextElement.prototype.emptyProp = new LetterProps(), ITextElement.prototype.destroy = function () {}, extendPrototype([BaseElement, TransformElement, HierarchyElement, FrameElement, RenderableDOMElement], ICompElement), ICompElement.prototype.initElement = function (t, e, r) {
    this.initFrame(), this.initBaseData(t, e, r), this.initTransform(t, e, r), this.initRenderable(), this.initHierarchy(), this.initRendererElement(), this.createContainerElements(), this.createRenderableComponents(), !this.data.xt && e.progressiveLoad || this.buildAllItems(), this.hide();
  }, ICompElement.prototype.prepareFrame = function (t) {
    if (this._mdf = !1, this.prepareRenderableFrame(t), this.prepareProperties(t, this.isInRange), this.isInRange || this.data.xt) {
      if (this.tm._placeholder) this.renderedFrame = t / this.data.sr;else {
        var e = this.tm.v;
        e === this.data.op && (e = this.data.op - 1), this.renderedFrame = e;
      }
      var r,
          i = this.elements.length;

      for (this.completeLayers || this.checkLayers(this.renderedFrame), r = i - 1; 0 <= r; r -= 1) {
        (this.completeLayers || this.elements[r]) && (this.elements[r].prepareFrame(this.renderedFrame - this.layers[r].st), this.elements[r]._mdf && (this._mdf = !0));
      }
    }
  }, ICompElement.prototype.renderInnerContent = function () {
    var t,
        e = this.layers.length;

    for (t = 0; t < e; t += 1) {
      (this.completeLayers || this.elements[t]) && this.elements[t].renderFrame();
    }
  }, ICompElement.prototype.setElements = function (t) {
    this.elements = t;
  }, ICompElement.prototype.getElements = function () {
    return this.elements;
  }, ICompElement.prototype.destroyElements = function () {
    var t,
        e = this.layers.length;

    for (t = 0; t < e; t += 1) {
      this.elements[t] && this.elements[t].destroy();
    }
  }, ICompElement.prototype.destroy = function () {
    this.destroyElements(), this.destroyBaseElement();
  }, extendPrototype([BaseElement, TransformElement, SVGBaseElement, HierarchyElement, FrameElement, RenderableDOMElement], IImageElement), IImageElement.prototype.createContent = function () {
    var t = this.globalData.getAssetsPath(this.assetData);
    this.innerElem = createNS("image"), this.innerElem.setAttribute("width", this.assetData.w + "px"), this.innerElem.setAttribute("height", this.assetData.h + "px"), this.innerElem.setAttribute("preserveAspectRatio", this.assetData.pr || this.globalData.renderConfig.imagePreserveAspectRatio), this.innerElem.setAttributeNS("http://www.w3.org/1999/xlink", "href", t), this.layerElement.appendChild(this.innerElem);
  }, IImageElement.prototype.sourceRectAtTime = function () {
    return this.sourceRect;
  }, extendPrototype([IImageElement], ISolidElement), ISolidElement.prototype.createContent = function () {
    var t = createNS("rect");
    t.setAttribute("width", this.data.sw), t.setAttribute("height", this.data.sh), t.setAttribute("fill", this.data.sc), this.layerElement.appendChild(t);
  }, AudioElement.prototype.prepareFrame = function (t) {
    if (this.prepareRenderableFrame(t, !0), this.prepareProperties(t, !0), this.tm._placeholder) this._currentTime = t / this.data.sr;else {
      var e = this.tm.v;
      this._currentTime = e;
    }
  }, extendPrototype([RenderableElement, BaseElement, FrameElement], AudioElement), AudioElement.prototype.renderFrame = function () {
    this.isInRange && this._canPlay && (this._isPlaying ? (!this.audio.playing() || .1 < Math.abs(this._currentTime / this.globalData.frameRate - this.audio.seek())) && this.audio.seek(this._currentTime / this.globalData.frameRate) : (this.audio.play(), this.audio.seek(this._currentTime / this.globalData.frameRate), this._isPlaying = !0));
  }, AudioElement.prototype.show = function () {}, AudioElement.prototype.hide = function () {
    this.audio.pause(), this._isPlaying = !1;
  }, AudioElement.prototype.pause = function () {
    this.audio.pause(), this._isPlaying = !1, this._canPlay = !1;
  }, AudioElement.prototype.resume = function () {
    this._canPlay = !0;
  }, AudioElement.prototype.setRate = function (t) {
    this.audio.rate(t);
  }, AudioElement.prototype.volume = function (t) {
    this.audio.volume(t);
  }, AudioElement.prototype.getBaseElement = function () {
    return null;
  }, AudioElement.prototype.destroy = function () {}, AudioElement.prototype.sourceRectAtTime = function () {}, AudioElement.prototype.initExpressions = function () {}, FootageElement.prototype.prepareFrame = function () {}, extendPrototype([RenderableElement, BaseElement, FrameElement], FootageElement), FootageElement.prototype.getBaseElement = function () {
    return null;
  }, FootageElement.prototype.renderFrame = function () {}, FootageElement.prototype.destroy = function () {}, FootageElement.prototype.initExpressions = function () {
    this.layerInterface = FootageInterface(this);
  }, FootageElement.prototype.getFootageData = function () {
    return this.footageData;
  }, extendPrototype([SVGRenderer, ICompElement, SVGBaseElement], SVGCompElement), extendPrototype([BaseElement, TransformElement, SVGBaseElement, HierarchyElement, FrameElement, RenderableDOMElement, ITextElement], SVGTextLottieElement), SVGTextLottieElement.prototype.createContent = function () {
    this.data.singleShape && !this.globalData.fontManager.chars && (this.textContainer = createNS("text"));
  }, SVGTextLottieElement.prototype.buildTextContents = function (t) {
    for (var e = 0, r = t.length, i = [], s = ""; e < r;) {
      t[e] === String.fromCharCode(13) || t[e] === String.fromCharCode(3) ? (i.push(s), s = "") : s += t[e], e += 1;
    }

    return i.push(s), i;
  }, SVGTextLottieElement.prototype.buildNewText = function () {
    var t,
        e,
        r = this.textProperty.currentData;
    this.renderedLetters = createSizedArray(r ? r.l.length : 0), r.fc ? this.layerElement.setAttribute("fill", this.buildColor(r.fc)) : this.layerElement.setAttribute("fill", "rgba(0,0,0,0)"), r.sc && (this.layerElement.setAttribute("stroke", this.buildColor(r.sc)), this.layerElement.setAttribute("stroke-width", r.sw)), this.layerElement.setAttribute("font-size", r.finalSize);
    var i = this.globalData.fontManager.getFontByName(r.f);
    if (i.fClass) this.layerElement.setAttribute("class", i.fClass);else {
      this.layerElement.setAttribute("font-family", i.fFamily);
      var s = r.fWeight,
          a = r.fStyle;
      this.layerElement.setAttribute("font-style", a), this.layerElement.setAttribute("font-weight", s);
    }
    this.layerElement.setAttribute("aria-label", r.t);
    var n,
        o = r.l || [],
        h = !!this.globalData.fontManager.chars;
    e = o.length;
    var l,
        p = this.mHelper,
        m = "",
        f = this.data.singleShape,
        c = 0,
        d = 0,
        u = !0,
        y = .001 * r.tr * r.finalSize;

    if (!f || h || r.sz) {
      var g,
          v,
          b = this.textSpans.length;

      for (t = 0; t < e; t += 1) {
        h && f && 0 !== t || (n = t < b ? this.textSpans[t] : createNS(h ? "path" : "text"), b <= t && (n.setAttribute("stroke-linecap", "butt"), n.setAttribute("stroke-linejoin", "round"), n.setAttribute("stroke-miterlimit", "4"), this.textSpans[t] = n, this.layerElement.appendChild(n)), n.style.display = "inherit"), p.reset(), p.scale(r.finalSize / 100, r.finalSize / 100), f && (o[t].n && (c = -y, d += r.yOffset, d += u ? 1 : 0, u = !1), this.applyTextPropertiesToMatrix(r, p, o[t].line, c, d), c += o[t].l || 0, c += y), h ? (l = (g = (v = this.globalData.fontManager.getCharData(r.finalText[t], i.fStyle, this.globalData.fontManager.getFontByName(r.f).fFamily)) && v.data || {}).shapes ? g.shapes[0].it : [], f ? m += this.createPathShape(p, l) : n.setAttribute("d", this.createPathShape(p, l))) : (f && n.setAttribute("transform", "translate(" + p.props[12] + "," + p.props[13] + ")"), n.textContent = o[t].val, n.setAttributeNS("http://www.w3.org/XML/1998/namespace", "xml:space", "preserve"));
      }

      f && n && n.setAttribute("d", m);
    } else {
      var P = this.textContainer,
          E = "start";

      switch (r.j) {
        case 1:
          E = "end";
          break;

        case 2:
          E = "middle";
          break;

        default:
          E = "start";
      }

      P.setAttribute("text-anchor", E), P.setAttribute("letter-spacing", y);
      var x = this.buildTextContents(r.finalText);

      for (e = x.length, d = r.ps ? r.ps[1] + r.ascent : 0, t = 0; t < e; t += 1) {
        (n = this.textSpans[t] || createNS("tspan")).textContent = x[t], n.setAttribute("x", 0), n.setAttribute("y", d), n.style.display = "inherit", P.appendChild(n), this.textSpans[t] = n, d += r.finalLineHeight;
      }

      this.layerElement.appendChild(P);
    }

    for (; t < this.textSpans.length;) {
      this.textSpans[t].style.display = "none", t += 1;
    }

    this._sizeChanged = !0;
  }, SVGTextLottieElement.prototype.sourceRectAtTime = function () {
    if (this.prepareFrame(this.comp.renderedFrame - this.data.st), this.renderInnerContent(), this._sizeChanged) {
      this._sizeChanged = !1;
      var t = this.layerElement.getBBox();
      this.bbox = {
        top: t.y,
        left: t.x,
        width: t.width,
        height: t.height
      };
    }

    return this.bbox;
  }, SVGTextLottieElement.prototype.renderInnerContent = function () {
    if (!this.data.singleShape && (this.textAnimator.getMeasures(this.textProperty.currentData, this.lettersChangedFlag), this.lettersChangedFlag || this.textAnimator.lettersChangedFlag)) {
      var t, e;
      this._sizeChanged = !0;
      var r,
          i,
          s = this.textAnimator.renderedLetters,
          a = this.textProperty.currentData.l;

      for (e = a.length, t = 0; t < e; t += 1) {
        a[t].n || (r = s[t], i = this.textSpans[t], r._mdf.m && i.setAttribute("transform", r.m), r._mdf.o && i.setAttribute("opacity", r.o), r._mdf.sw && i.setAttribute("stroke-width", r.sw), r._mdf.sc && i.setAttribute("stroke", r.sc), r._mdf.fc && i.setAttribute("fill", r.fc));
      }
    }
  }, extendPrototype([BaseElement, TransformElement, SVGBaseElement, IShapeElement, HierarchyElement, FrameElement, RenderableDOMElement], SVGShapeElement), SVGShapeElement.prototype.initSecondaryElement = function () {}, SVGShapeElement.prototype.identityMatrix = new Matrix(), SVGShapeElement.prototype.buildExpressionInterface = function () {}, SVGShapeElement.prototype.createContent = function () {
    this.searchShapes(this.shapesData, this.itemsData, this.prevViewData, this.layerElement, 0, [], !0), this.filterUniqueShapes();
  }, SVGShapeElement.prototype.filterUniqueShapes = function () {
    var t,
        e,
        r,
        i,
        s = this.shapes.length,
        a = this.stylesList.length,
        n = [],
        o = !1;

    for (r = 0; r < a; r += 1) {
      for (i = this.stylesList[r], o = !1, t = n.length = 0; t < s; t += 1) {
        -1 !== (e = this.shapes[t]).styles.indexOf(i) && (n.push(e), o = e._isAnimated || o);
      }

      1 < n.length && o && this.setShapesAsAnimated(n);
    }
  }, SVGShapeElement.prototype.setShapesAsAnimated = function (t) {
    var e,
        r = t.length;

    for (e = 0; e < r; e += 1) {
      t[e].setAsAnimated();
    }
  }, SVGShapeElement.prototype.createStyleElement = function (t, e) {
    var r,
        i = new SVGStyleData(t, e),
        s = i.pElem;
    if ("st" === t.ty) r = new SVGStrokeStyleData(this, t, i);else if ("fl" === t.ty) r = new SVGFillStyleData(this, t, i);else if ("gf" === t.ty || "gs" === t.ty) {
      r = new ("gf" === t.ty ? SVGGradientFillStyleData : SVGGradientStrokeStyleData)(this, t, i), this.globalData.defs.appendChild(r.gf), r.maskId && (this.globalData.defs.appendChild(r.ms), this.globalData.defs.appendChild(r.of), s.setAttribute("mask", "url(" + locationHref + "#" + r.maskId + ")"));
    }
    return "st" !== t.ty && "gs" !== t.ty || (s.setAttribute("stroke-linecap", lineCapEnum[t.lc || 2]), s.setAttribute("stroke-linejoin", lineJoinEnum[t.lj || 2]), s.setAttribute("fill-opacity", "0"), 1 === t.lj && s.setAttribute("stroke-miterlimit", t.ml)), 2 === t.r && s.setAttribute("fill-rule", "evenodd"), t.ln && s.setAttribute("id", t.ln), t.cl && s.setAttribute("class", t.cl), t.bm && (s.style["mix-blend-mode"] = getBlendMode(t.bm)), this.stylesList.push(i), this.addToAnimatedContents(t, r), r;
  }, SVGShapeElement.prototype.createGroupElement = function (t) {
    var e = new ShapeGroupData();
    return t.ln && e.gr.setAttribute("id", t.ln), t.cl && e.gr.setAttribute("class", t.cl), t.bm && (e.gr.style["mix-blend-mode"] = getBlendMode(t.bm)), e;
  }, SVGShapeElement.prototype.createTransformElement = function (t, e) {
    var r = TransformPropertyFactory.getTransformProperty(this, t, this),
        i = new SVGTransformData(r, r.o, e);
    return this.addToAnimatedContents(t, i), i;
  }, SVGShapeElement.prototype.createShapeElement = function (t, e, r) {
    var i = 4;
    "rc" === t.ty ? i = 5 : "el" === t.ty ? i = 6 : "sr" === t.ty && (i = 7);
    var s = new SVGShapeData(e, r, ShapePropertyFactory.getShapeProp(this, t, i, this));
    return this.shapes.push(s), this.addShapeToModifiers(s), this.addToAnimatedContents(t, s), s;
  }, SVGShapeElement.prototype.addToAnimatedContents = function (t, e) {
    for (var r = 0, i = this.animatedContents.length; r < i;) {
      if (this.animatedContents[r].element === e) return;
      r += 1;
    }

    this.animatedContents.push({
      fn: SVGElementsRenderer.createRenderFunction(t),
      element: e,
      data: t
    });
  }, SVGShapeElement.prototype.setElementStyles = function (t) {
    var e,
        r = t.styles,
        i = this.stylesList.length;

    for (e = 0; e < i; e += 1) {
      this.stylesList[e].closed || r.push(this.stylesList[e]);
    }
  }, SVGShapeElement.prototype.reloadShapes = function () {
    var t;
    this._isFirstFrame = !0;
    var e = this.itemsData.length;

    for (t = 0; t < e; t += 1) {
      this.prevViewData[t] = this.itemsData[t];
    }

    for (this.searchShapes(this.shapesData, this.itemsData, this.prevViewData, this.layerElement, 0, [], !0), this.filterUniqueShapes(), e = this.dynamicProperties.length, t = 0; t < e; t += 1) {
      this.dynamicProperties[t].getValue();
    }

    this.renderModifiers();
  }, SVGShapeElement.prototype.searchShapes = function (t, e, r, i, s, a, n) {
    var o,
        h,
        l,
        p,
        m,
        f,
        c = [].concat(a),
        d = t.length - 1,
        u = [],
        y = [];

    for (o = d; 0 <= o; o -= 1) {
      if ((f = this.searchProcessedElement(t[o])) ? e[o] = r[f - 1] : t[o]._render = n, "fl" === t[o].ty || "st" === t[o].ty || "gf" === t[o].ty || "gs" === t[o].ty) f ? e[o].style.closed = !1 : e[o] = this.createStyleElement(t[o], s), t[o]._render && i.appendChild(e[o].style.pElem), u.push(e[o].style);else if ("gr" === t[o].ty) {
        if (f) for (l = e[o].it.length, h = 0; h < l; h += 1) {
          e[o].prevViewData[h] = e[o].it[h];
        } else e[o] = this.createGroupElement(t[o]);
        this.searchShapes(t[o].it, e[o].it, e[o].prevViewData, e[o].gr, s + 1, c, n), t[o]._render && i.appendChild(e[o].gr);
      } else "tr" === t[o].ty ? (f || (e[o] = this.createTransformElement(t[o], i)), p = e[o].transform, c.push(p)) : "sh" === t[o].ty || "rc" === t[o].ty || "el" === t[o].ty || "sr" === t[o].ty ? (f || (e[o] = this.createShapeElement(t[o], c, s)), this.setElementStyles(e[o])) : "tm" === t[o].ty || "rd" === t[o].ty || "ms" === t[o].ty || "pb" === t[o].ty ? (f ? (m = e[o]).closed = !1 : ((m = ShapeModifiers.getModifier(t[o].ty)).init(this, t[o]), e[o] = m, this.shapeModifiers.push(m)), y.push(m)) : "rp" === t[o].ty && (f ? (m = e[o]).closed = !0 : (m = ShapeModifiers.getModifier(t[o].ty), (e[o] = m).init(this, t, o, e), this.shapeModifiers.push(m), n = !1), y.push(m));
      this.addProcessedElement(t[o], o + 1);
    }

    for (d = u.length, o = 0; o < d; o += 1) {
      u[o].closed = !0;
    }

    for (d = y.length, o = 0; o < d; o += 1) {
      y[o].closed = !0;
    }
  }, SVGShapeElement.prototype.renderInnerContent = function () {
    var t;
    this.renderModifiers();
    var e = this.stylesList.length;

    for (t = 0; t < e; t += 1) {
      this.stylesList[t].reset();
    }

    for (this.renderShape(), t = 0; t < e; t += 1) {
      (this.stylesList[t]._mdf || this._isFirstFrame) && (this.stylesList[t].msElem && (this.stylesList[t].msElem.setAttribute("d", this.stylesList[t].d), this.stylesList[t].d = "M0 0" + this.stylesList[t].d), this.stylesList[t].pElem.setAttribute("d", this.stylesList[t].d || "M0 0"));
    }
  }, SVGShapeElement.prototype.renderShape = function () {
    var t,
        e,
        r = this.animatedContents.length;

    for (t = 0; t < r; t += 1) {
      e = this.animatedContents[t], (this._isFirstFrame || e.element._isAnimated) && !0 !== e.data && e.fn(e.data, e.element, this._isFirstFrame);
    }
  }, SVGShapeElement.prototype.destroy = function () {
    this.destroyBaseElement(), this.shapesData = null, this.itemsData = null;
  }, SVGTintFilter.prototype.renderFrame = function (t) {
    if (t || this.filterManager._mdf) {
      var e = this.filterManager.effectElements[0].p.v,
          r = this.filterManager.effectElements[1].p.v,
          i = this.filterManager.effectElements[2].p.v / 100;
      this.matrixFilter.setAttribute("values", r[0] - e[0] + " 0 0 0 " + e[0] + " " + (r[1] - e[1]) + " 0 0 0 " + e[1] + " " + (r[2] - e[2]) + " 0 0 0 " + e[2] + " 0 0 0 " + i + " 0");
    }
  }, SVGFillFilter.prototype.renderFrame = function (t) {
    if (t || this.filterManager._mdf) {
      var e = this.filterManager.effectElements[2].p.v,
          r = this.filterManager.effectElements[6].p.v;
      this.matrixFilter.setAttribute("values", "0 0 0 0 " + e[0] + " 0 0 0 0 " + e[1] + " 0 0 0 0 " + e[2] + " 0 0 0 " + r + " 0");
    }
  }, SVGGaussianBlurEffect.prototype.renderFrame = function (t) {
    if (t || this.filterManager._mdf) {
      var e = .3 * this.filterManager.effectElements[0].p.v,
          r = this.filterManager.effectElements[1].p.v,
          i = 3 == r ? 0 : e,
          s = 2 == r ? 0 : e;
      this.feGaussianBlur.setAttribute("stdDeviation", i + " " + s);
      var a = 1 == this.filterManager.effectElements[2].p.v ? "wrap" : "duplicate";
      this.feGaussianBlur.setAttribute("edgeMode", a);
    }
  }, SVGStrokeEffect.prototype.initialize = function () {
    var t,
        e,
        r,
        i,
        s = this.elem.layerElement.children || this.elem.layerElement.childNodes;

    for (1 === this.filterManager.effectElements[1].p.v ? (i = this.elem.maskManager.masksProperties.length, r = 0) : i = (r = this.filterManager.effectElements[0].p.v - 1) + 1, (e = createNS("g")).setAttribute("fill", "none"), e.setAttribute("stroke-linecap", "round"), e.setAttribute("stroke-dashoffset", 1); r < i; r += 1) {
      t = createNS("path"), e.appendChild(t), this.paths.push({
        p: t,
        m: r
      });
    }

    if (3 === this.filterManager.effectElements[10].p.v) {
      var a = createNS("mask"),
          n = createElementID();
      a.setAttribute("id", n), a.setAttribute("mask-type", "alpha"), a.appendChild(e), this.elem.globalData.defs.appendChild(a);
      var o = createNS("g");

      for (o.setAttribute("mask", "url(" + locationHref + "#" + n + ")"); s[0];) {
        o.appendChild(s[0]);
      }

      this.elem.layerElement.appendChild(o), this.masker = a, e.setAttribute("stroke", "#fff");
    } else if (1 === this.filterManager.effectElements[10].p.v || 2 === this.filterManager.effectElements[10].p.v) {
      if (2 === this.filterManager.effectElements[10].p.v) for (s = this.elem.layerElement.children || this.elem.layerElement.childNodes; s.length;) {
        this.elem.layerElement.removeChild(s[0]);
      }
      this.elem.layerElement.appendChild(e), this.elem.layerElement.removeAttribute("mask"), e.setAttribute("stroke", "#fff");
    }

    this.initialized = !0, this.pathMasker = e;
  }, SVGStrokeEffect.prototype.renderFrame = function (t) {
    var e;
    this.initialized || this.initialize();
    var r,
        i,
        s = this.paths.length;

    for (e = 0; e < s; e += 1) {
      if (-1 !== this.paths[e].m && (r = this.elem.maskManager.viewData[this.paths[e].m], i = this.paths[e].p, (t || this.filterManager._mdf || r.prop._mdf) && i.setAttribute("d", r.lastPath), t || this.filterManager.effectElements[9].p._mdf || this.filterManager.effectElements[4].p._mdf || this.filterManager.effectElements[7].p._mdf || this.filterManager.effectElements[8].p._mdf || r.prop._mdf)) {
        var a;

        if (0 !== this.filterManager.effectElements[7].p.v || 100 !== this.filterManager.effectElements[8].p.v) {
          var n = .01 * Math.min(this.filterManager.effectElements[7].p.v, this.filterManager.effectElements[8].p.v),
              o = .01 * Math.max(this.filterManager.effectElements[7].p.v, this.filterManager.effectElements[8].p.v),
              h = i.getTotalLength();
          a = "0 0 0 " + h * n + " ";
          var l,
              p = h * (o - n),
              m = 1 + 2 * this.filterManager.effectElements[4].p.v * this.filterManager.effectElements[9].p.v * .01,
              f = Math.floor(p / m);

          for (l = 0; l < f; l += 1) {
            a += "1 " + 2 * this.filterManager.effectElements[4].p.v * this.filterManager.effectElements[9].p.v * .01 + " ";
          }

          a += "0 " + 10 * h + " 0 0";
        } else a = "1 " + 2 * this.filterManager.effectElements[4].p.v * this.filterManager.effectElements[9].p.v * .01;

        i.setAttribute("stroke-dasharray", a);
      }
    }

    if ((t || this.filterManager.effectElements[4].p._mdf) && this.pathMasker.setAttribute("stroke-width", 2 * this.filterManager.effectElements[4].p.v), (t || this.filterManager.effectElements[6].p._mdf) && this.pathMasker.setAttribute("opacity", this.filterManager.effectElements[6].p.v), (1 === this.filterManager.effectElements[10].p.v || 2 === this.filterManager.effectElements[10].p.v) && (t || this.filterManager.effectElements[3].p._mdf)) {
      var c = this.filterManager.effectElements[3].p.v;
      this.pathMasker.setAttribute("stroke", "rgb(" + bmFloor(255 * c[0]) + "," + bmFloor(255 * c[1]) + "," + bmFloor(255 * c[2]) + ")");
    }
  }, SVGTritoneFilter.prototype.renderFrame = function (t) {
    if (t || this.filterManager._mdf) {
      var e = this.filterManager.effectElements[0].p.v,
          r = this.filterManager.effectElements[1].p.v,
          i = this.filterManager.effectElements[2].p.v,
          s = i[0] + " " + r[0] + " " + e[0],
          a = i[1] + " " + r[1] + " " + e[1],
          n = i[2] + " " + r[2] + " " + e[2];
      this.feFuncR.setAttribute("tableValues", s), this.feFuncG.setAttribute("tableValues", a), this.feFuncB.setAttribute("tableValues", n);
    }
  }, SVGProLevelsFilter.prototype.createFeFunc = function (t, e) {
    var r = createNS(t);
    return r.setAttribute("type", "table"), e.appendChild(r), r;
  }, SVGProLevelsFilter.prototype.getTableValue = function (t, e, r, i, s) {
    for (var a, n, o = 0, h = Math.min(t, e), l = Math.max(t, e), p = Array.call(null, {
      length: 256
    }), m = 0, f = s - i, c = e - t; o <= 256;) {
      n = (a = o / 256) <= h ? c < 0 ? s : i : l <= a ? c < 0 ? i : s : i + f * Math.pow((a - t) / c, 1 / r), p[m] = n, m += 1, o += 256 / 255;
    }

    return p.join(" ");
  }, SVGProLevelsFilter.prototype.renderFrame = function (t) {
    if (t || this.filterManager._mdf) {
      var e,
          r = this.filterManager.effectElements;
      this.feFuncRComposed && (t || r[3].p._mdf || r[4].p._mdf || r[5].p._mdf || r[6].p._mdf || r[7].p._mdf) && (e = this.getTableValue(r[3].p.v, r[4].p.v, r[5].p.v, r[6].p.v, r[7].p.v), this.feFuncRComposed.setAttribute("tableValues", e), this.feFuncGComposed.setAttribute("tableValues", e), this.feFuncBComposed.setAttribute("tableValues", e)), this.feFuncR && (t || r[10].p._mdf || r[11].p._mdf || r[12].p._mdf || r[13].p._mdf || r[14].p._mdf) && (e = this.getTableValue(r[10].p.v, r[11].p.v, r[12].p.v, r[13].p.v, r[14].p.v), this.feFuncR.setAttribute("tableValues", e)), this.feFuncG && (t || r[17].p._mdf || r[18].p._mdf || r[19].p._mdf || r[20].p._mdf || r[21].p._mdf) && (e = this.getTableValue(r[17].p.v, r[18].p.v, r[19].p.v, r[20].p.v, r[21].p.v), this.feFuncG.setAttribute("tableValues", e)), this.feFuncB && (t || r[24].p._mdf || r[25].p._mdf || r[26].p._mdf || r[27].p._mdf || r[28].p._mdf) && (e = this.getTableValue(r[24].p.v, r[25].p.v, r[26].p.v, r[27].p.v, r[28].p.v), this.feFuncB.setAttribute("tableValues", e)), this.feFuncA && (t || r[31].p._mdf || r[32].p._mdf || r[33].p._mdf || r[34].p._mdf || r[35].p._mdf) && (e = this.getTableValue(r[31].p.v, r[32].p.v, r[33].p.v, r[34].p.v, r[35].p.v), this.feFuncA.setAttribute("tableValues", e));
    }
  }, SVGDropShadowEffect.prototype.renderFrame = function (t) {
    if (t || this.filterManager._mdf) {
      if ((t || this.filterManager.effectElements[4].p._mdf) && this.feGaussianBlur.setAttribute("stdDeviation", this.filterManager.effectElements[4].p.v / 4), t || this.filterManager.effectElements[0].p._mdf) {
        var e = this.filterManager.effectElements[0].p.v;
        this.feFlood.setAttribute("flood-color", rgbToHex(Math.round(255 * e[0]), Math.round(255 * e[1]), Math.round(255 * e[2])));
      }

      if ((t || this.filterManager.effectElements[1].p._mdf) && this.feFlood.setAttribute("flood-opacity", this.filterManager.effectElements[1].p.v / 255), t || this.filterManager.effectElements[2].p._mdf || this.filterManager.effectElements[3].p._mdf) {
        var r = this.filterManager.effectElements[3].p.v,
            i = (this.filterManager.effectElements[2].p.v - 90) * degToRads,
            s = r * Math.cos(i),
            a = r * Math.sin(i);
        this.feOffset.setAttribute("dx", s), this.feOffset.setAttribute("dy", a);
      }
    }
  };
  var _svgMatteSymbols = [];

  function SVGMatte3Effect(t, e, r) {
    this.initialized = !1, this.filterManager = e, this.filterElem = t, (this.elem = r).matteElement = createNS("g"), r.matteElement.appendChild(r.layerElement), r.matteElement.appendChild(r.transformedElement), r.baseElement = r.matteElement;
  }

  function SVGEffects(t) {
    var e,
        r,
        i = t.data.ef ? t.data.ef.length : 0,
        s = createElementID(),
        a = filtersFactory.createFilter(s, !0),
        n = 0;

    for (this.filters = [], e = 0; e < i; e += 1) {
      r = null, 20 === t.data.ef[e].ty ? (n += 1, r = new SVGTintFilter(a, t.effectsManager.effectElements[e])) : 21 === t.data.ef[e].ty ? (n += 1, r = new SVGFillFilter(a, t.effectsManager.effectElements[e])) : 22 === t.data.ef[e].ty ? r = new SVGStrokeEffect(t, t.effectsManager.effectElements[e]) : 23 === t.data.ef[e].ty ? (n += 1, r = new SVGTritoneFilter(a, t.effectsManager.effectElements[e])) : 24 === t.data.ef[e].ty ? (n += 1, r = new SVGProLevelsFilter(a, t.effectsManager.effectElements[e])) : 25 === t.data.ef[e].ty ? (n += 1, r = new SVGDropShadowEffect(a, t.effectsManager.effectElements[e])) : 28 === t.data.ef[e].ty ? r = new SVGMatte3Effect(a, t.effectsManager.effectElements[e], t) : 29 === t.data.ef[e].ty && (n += 1, r = new SVGGaussianBlurEffect(a, t.effectsManager.effectElements[e])), r && this.filters.push(r);
    }

    n && (t.globalData.defs.appendChild(a), t.layerElement.setAttribute("filter", "url(" + locationHref + "#" + s + ")")), this.filters.length && t.addRenderableComponent(this);
  }

  function CVContextData() {
    var t;
    this.saved = [], this.cArrPos = 0, this.cTr = new Matrix(), this.cO = 1;

    for (this.savedOp = createTypedArray("float32", 15), t = 0; t < 15; t += 1) {
      this.saved[t] = createTypedArray("float32", 16);
    }

    this._length = 15;
  }

  function CVBaseElement() {}

  function CVImageElement(t, e, r) {
    this.assetData = e.getAssetData(t.refId), this.img = e.imageLoader.getAsset(this.assetData), this.initElement(t, e, r);
  }

  function CVCompElement(t, e, r) {
    this.completeLayers = !1, this.layers = t.layers, this.pendingElements = [], this.elements = createSizedArray(this.layers.length), this.initElement(t, e, r), this.tm = t.tm ? PropertyFactory.getProp(this, t.tm, 0, e.frameRate, this) : {
      _placeholder: !0
    };
  }

  function CVMaskElement(t, e) {
    var r;
    this.data = t, this.element = e, this.masksProperties = this.data.masksProperties || [], this.viewData = createSizedArray(this.masksProperties.length);
    var i = this.masksProperties.length,
        s = !1;

    for (r = 0; r < i; r += 1) {
      "n" !== this.masksProperties[r].mode && (s = !0), this.viewData[r] = ShapePropertyFactory.getShapeProp(this.element, this.masksProperties[r], 3);
    }

    (this.hasMasks = s) && this.element.addRenderableComponent(this);
  }

  function CVShapeElement(t, e, r) {
    this.shapes = [], this.shapesData = t.shapes, this.stylesList = [], this.itemsData = [], this.prevViewData = [], this.shapeModifiers = [], this.processedElements = [], this.transformsManager = new ShapeTransformManager(), this.initElement(t, e, r);
  }

  function CVSolidElement(t, e, r) {
    this.initElement(t, e, r);
  }

  function CVTextElement(t, e, r) {
    this.textSpans = [], this.yOffset = 0, this.fillColorAnim = !1, this.strokeColorAnim = !1, this.strokeWidthAnim = !1, this.stroke = !1, this.fill = !1, this.justifyOffset = 0, this.currentRender = null, this.renderType = "canvas", this.values = {
      fill: "rgba(0,0,0,0)",
      stroke: "rgba(0,0,0,0)",
      sWidth: 0,
      fValue: ""
    }, this.initElement(t, e, r);
  }

  function CVEffects() {}

  function HBaseElement() {}

  function HSolidElement(t, e, r) {
    this.initElement(t, e, r);
  }

  function HCompElement(t, e, r) {
    this.layers = t.layers, this.supports3d = !t.hasMask, this.completeLayers = !1, this.pendingElements = [], this.elements = this.layers ? createSizedArray(this.layers.length) : [], this.initElement(t, e, r), this.tm = t.tm ? PropertyFactory.getProp(this, t.tm, 0, e.frameRate, this) : {
      _placeholder: !0
    };
  }

  function HShapeElement(t, e, r) {
    this.shapes = [], this.shapesData = t.shapes, this.stylesList = [], this.shapeModifiers = [], this.itemsData = [], this.processedElements = [], this.animatedContents = [], this.shapesContainer = createNS("g"), this.initElement(t, e, r), this.prevViewData = [], this.currentBBox = {
      x: 999999,
      y: -999999,
      h: 0,
      w: 0
    };
  }

  function HTextElement(t, e, r) {
    this.textSpans = [], this.textPaths = [], this.currentBBox = {
      x: 999999,
      y: -999999,
      h: 0,
      w: 0
    }, this.renderType = "svg", this.isMasked = !1, this.initElement(t, e, r);
  }

  function HImageElement(t, e, r) {
    this.assetData = e.getAssetData(t.refId), this.initElement(t, e, r);
  }

  function HCameraElement(t, e, r) {
    this.initFrame(), this.initBaseData(t, e, r), this.initHierarchy();
    var i = PropertyFactory.getProp;

    if (this.pe = i(this, t.pe, 0, 0, this), t.ks.p.s ? (this.px = i(this, t.ks.p.x, 1, 0, this), this.py = i(this, t.ks.p.y, 1, 0, this), this.pz = i(this, t.ks.p.z, 1, 0, this)) : this.p = i(this, t.ks.p, 1, 0, this), t.ks.a && (this.a = i(this, t.ks.a, 1, 0, this)), t.ks.or.k.length && t.ks.or.k[0].to) {
      var s,
          a = t.ks.or.k.length;

      for (s = 0; s < a; s += 1) {
        t.ks.or.k[s].to = null, t.ks.or.k[s].ti = null;
      }
    }

    this.or = i(this, t.ks.or, 1, degToRads, this), this.or.sh = !0, this.rx = i(this, t.ks.rx, 0, degToRads, this), this.ry = i(this, t.ks.ry, 0, degToRads, this), this.rz = i(this, t.ks.rz, 0, degToRads, this), this.mat = new Matrix(), this._prevMat = new Matrix(), this._isFirstFrame = !0, this.finalTransform = {
      mProp: this
    };
  }

  function HEffects() {}

  SVGMatte3Effect.prototype.findSymbol = function (t) {
    for (var e = 0, r = _svgMatteSymbols.length; e < r;) {
      if (_svgMatteSymbols[e] === t) return _svgMatteSymbols[e];
      e += 1;
    }

    return null;
  }, SVGMatte3Effect.prototype.replaceInParent = function (t, e) {
    var r = t.layerElement.parentNode;

    if (r) {
      for (var i, s = r.children, a = 0, n = s.length; a < n && s[a] !== t.layerElement;) {
        a += 1;
      }

      a <= n - 2 && (i = s[a + 1]);
      var o = createNS("use");
      o.setAttribute("href", "#" + e), i ? r.insertBefore(o, i) : r.appendChild(o);
    }
  }, SVGMatte3Effect.prototype.setElementAsMask = function (t, e) {
    if (!this.findSymbol(e)) {
      var r = createElementID(),
          i = createNS("mask");
      i.setAttribute("id", e.layerId), i.setAttribute("mask-type", "alpha"), _svgMatteSymbols.push(e);
      var s = t.globalData.defs;
      s.appendChild(i);
      var a = createNS("symbol");
      a.setAttribute("id", r), this.replaceInParent(e, r), a.appendChild(e.layerElement), s.appendChild(a);
      var n = createNS("use");
      n.setAttribute("href", "#" + r), i.appendChild(n), e.data.hd = !1, e.show();
    }

    t.setMatte(e.layerId);
  }, SVGMatte3Effect.prototype.initialize = function () {
    for (var t = this.filterManager.effectElements[0].p.v, e = this.elem.comp.elements, r = 0, i = e.length; r < i;) {
      e[r] && e[r].data.ind === t && this.setElementAsMask(this.elem, e[r]), r += 1;
    }

    this.initialized = !0;
  }, SVGMatte3Effect.prototype.renderFrame = function () {
    this.initialized || this.initialize();
  }, SVGEffects.prototype.renderFrame = function (t) {
    var e,
        r = this.filters.length;

    for (e = 0; e < r; e += 1) {
      this.filters[e].renderFrame(t);
    }
  }, CVContextData.prototype.duplicate = function () {
    var t = 2 * this._length,
        e = this.savedOp;
    this.savedOp = createTypedArray("float32", t), this.savedOp.set(e);
    var r = 0;

    for (r = this._length; r < t; r += 1) {
      this.saved[r] = createTypedArray("float32", 16);
    }

    this._length = t;
  }, CVContextData.prototype.reset = function () {
    this.cArrPos = 0, this.cTr.reset(), this.cO = 1;
  }, CVBaseElement.prototype = {
    createElements: function createElements() {},
    initRendererElement: function initRendererElement() {},
    createContainerElements: function createContainerElements() {
      this.canvasContext = this.globalData.canvasContext, this.renderableEffectsManager = new CVEffects(this);
    },
    createContent: function createContent() {},
    setBlendMode: function setBlendMode() {
      var t = this.globalData;

      if (t.blendMode !== this.data.bm) {
        t.blendMode = this.data.bm;
        var e = getBlendMode(this.data.bm);
        t.canvasContext.globalCompositeOperation = e;
      }
    },
    createRenderableComponents: function createRenderableComponents() {
      this.maskManager = new CVMaskElement(this.data, this);
    },
    hideElement: function hideElement() {
      this.hidden || this.isInRange && !this.isTransparent || (this.hidden = !0);
    },
    showElement: function showElement() {
      this.isInRange && !this.isTransparent && (this.hidden = !1, this._isFirstFrame = !0, this.maskManager._isFirstFrame = !0);
    },
    renderFrame: function renderFrame() {
      if (!this.hidden && !this.data.hd) {
        this.renderTransform(), this.renderRenderable(), this.setBlendMode();
        var t = 0 === this.data.ty;
        this.globalData.renderer.save(t), this.globalData.renderer.ctxTransform(this.finalTransform.mat.props), this.globalData.renderer.ctxOpacity(this.finalTransform.mProp.o.v), this.renderInnerContent(), this.globalData.renderer.restore(t), this.maskManager.hasMasks && this.globalData.renderer.restore(!0), this._isFirstFrame && (this._isFirstFrame = !1);
      }
    },
    destroy: function destroy() {
      this.canvasContext = null, this.data = null, this.globalData = null, this.maskManager.destroy();
    },
    mHelper: new Matrix()
  }, CVBaseElement.prototype.hide = CVBaseElement.prototype.hideElement, CVBaseElement.prototype.show = CVBaseElement.prototype.showElement, extendPrototype([BaseElement, TransformElement, CVBaseElement, HierarchyElement, FrameElement, RenderableElement], CVImageElement), CVImageElement.prototype.initElement = SVGShapeElement.prototype.initElement, CVImageElement.prototype.prepareFrame = IImageElement.prototype.prepareFrame, CVImageElement.prototype.createContent = function () {
    if (this.img.width && (this.assetData.w !== this.img.width || this.assetData.h !== this.img.height)) {
      var t = createTag("canvas");
      t.width = this.assetData.w, t.height = this.assetData.h;
      var e,
          r,
          i = t.getContext("2d"),
          s = this.img.width,
          a = this.img.height,
          n = s / a,
          o = this.assetData.w / this.assetData.h,
          h = this.assetData.pr || this.globalData.renderConfig.imagePreserveAspectRatio;
      o < n && "xMidYMid slice" === h || n < o && "xMidYMid slice" !== h ? e = (r = a) * o : r = (e = s) / o, i.drawImage(this.img, (s - e) / 2, (a - r) / 2, e, r, 0, 0, this.assetData.w, this.assetData.h), this.img = t;
    }
  }, CVImageElement.prototype.renderInnerContent = function () {
    this.canvasContext.drawImage(this.img, 0, 0);
  }, CVImageElement.prototype.destroy = function () {
    this.img = null;
  }, extendPrototype([CanvasRenderer, ICompElement, CVBaseElement], CVCompElement), CVCompElement.prototype.renderInnerContent = function () {
    var t,
        e = this.canvasContext;

    for (e.beginPath(), e.moveTo(0, 0), e.lineTo(this.data.w, 0), e.lineTo(this.data.w, this.data.h), e.lineTo(0, this.data.h), e.lineTo(0, 0), e.clip(), t = this.layers.length - 1; 0 <= t; t -= 1) {
      (this.completeLayers || this.elements[t]) && this.elements[t].renderFrame();
    }
  }, CVCompElement.prototype.destroy = function () {
    var t;

    for (t = this.layers.length - 1; 0 <= t; t -= 1) {
      this.elements[t] && this.elements[t].destroy();
    }

    this.layers = null, this.elements = null;
  }, CVMaskElement.prototype.renderFrame = function () {
    if (this.hasMasks) {
      var t,
          e,
          r,
          i,
          s = this.element.finalTransform.mat,
          a = this.element.canvasContext,
          n = this.masksProperties.length;

      for (a.beginPath(), t = 0; t < n; t += 1) {
        if ("n" !== this.masksProperties[t].mode) {
          var o;
          this.masksProperties[t].inv && (a.moveTo(0, 0), a.lineTo(this.element.globalData.compSize.w, 0), a.lineTo(this.element.globalData.compSize.w, this.element.globalData.compSize.h), a.lineTo(0, this.element.globalData.compSize.h), a.lineTo(0, 0)), i = this.viewData[t].v, e = s.applyToPointArray(i.v[0][0], i.v[0][1], 0), a.moveTo(e[0], e[1]);
          var h = i._length;

          for (o = 1; o < h; o += 1) {
            r = s.applyToTriplePoints(i.o[o - 1], i.i[o], i.v[o]), a.bezierCurveTo(r[0], r[1], r[2], r[3], r[4], r[5]);
          }

          r = s.applyToTriplePoints(i.o[o - 1], i.i[0], i.v[0]), a.bezierCurveTo(r[0], r[1], r[2], r[3], r[4], r[5]);
        }
      }

      this.element.globalData.renderer.save(!0), a.clip();
    }
  }, CVMaskElement.prototype.getMaskProperty = MaskElement.prototype.getMaskProperty, CVMaskElement.prototype.destroy = function () {
    this.element = null;
  }, extendPrototype([BaseElement, TransformElement, CVBaseElement, IShapeElement, HierarchyElement, FrameElement, RenderableElement], CVShapeElement), CVShapeElement.prototype.initElement = RenderableDOMElement.prototype.initElement, CVShapeElement.prototype.transformHelper = {
    opacity: 1,
    _opMdf: !1
  }, CVShapeElement.prototype.dashResetter = [], CVShapeElement.prototype.createContent = function () {
    this.searchShapes(this.shapesData, this.itemsData, this.prevViewData, !0, []);
  }, CVShapeElement.prototype.createStyleElement = function (t, e) {
    var r = {
      data: t,
      type: t.ty,
      preTransforms: this.transformsManager.addTransformSequence(e),
      transforms: [],
      elements: [],
      closed: !0 === t.hd
    },
        i = {};

    if ("fl" === t.ty || "st" === t.ty ? (i.c = PropertyFactory.getProp(this, t.c, 1, 255, this), i.c.k || (r.co = "rgb(" + bmFloor(i.c.v[0]) + "," + bmFloor(i.c.v[1]) + "," + bmFloor(i.c.v[2]) + ")")) : "gf" !== t.ty && "gs" !== t.ty || (i.s = PropertyFactory.getProp(this, t.s, 1, null, this), i.e = PropertyFactory.getProp(this, t.e, 1, null, this), i.h = PropertyFactory.getProp(this, t.h || {
      k: 0
    }, 0, .01, this), i.a = PropertyFactory.getProp(this, t.a || {
      k: 0
    }, 0, degToRads, this), i.g = new GradientProperty(this, t.g, this)), i.o = PropertyFactory.getProp(this, t.o, 0, .01, this), "st" === t.ty || "gs" === t.ty) {
      if (r.lc = lineCapEnum[t.lc || 2], r.lj = lineJoinEnum[t.lj || 2], 1 == t.lj && (r.ml = t.ml), i.w = PropertyFactory.getProp(this, t.w, 0, null, this), i.w.k || (r.wi = i.w.v), t.d) {
        var s = new DashProperty(this, t.d, "canvas", this);
        i.d = s, i.d.k || (r.da = i.d.dashArray, r["do"] = i.d.dashoffset[0]);
      }
    } else r.r = 2 === t.r ? "evenodd" : "nonzero";

    return this.stylesList.push(r), i.style = r, i;
  }, CVShapeElement.prototype.createGroupElement = function () {
    return {
      it: [],
      prevViewData: []
    };
  }, CVShapeElement.prototype.createTransformElement = function (t) {
    return {
      transform: {
        opacity: 1,
        _opMdf: !1,
        key: this.transformsManager.getNewKey(),
        op: PropertyFactory.getProp(this, t.o, 0, .01, this),
        mProps: TransformPropertyFactory.getTransformProperty(this, t, this)
      }
    };
  }, CVShapeElement.prototype.createShapeElement = function (t) {
    var e = new CVShapeData(this, t, this.stylesList, this.transformsManager);
    return this.shapes.push(e), this.addShapeToModifiers(e), e;
  }, CVShapeElement.prototype.reloadShapes = function () {
    var t;
    this._isFirstFrame = !0;
    var e = this.itemsData.length;

    for (t = 0; t < e; t += 1) {
      this.prevViewData[t] = this.itemsData[t];
    }

    for (this.searchShapes(this.shapesData, this.itemsData, this.prevViewData, !0, []), e = this.dynamicProperties.length, t = 0; t < e; t += 1) {
      this.dynamicProperties[t].getValue();
    }

    this.renderModifiers(), this.transformsManager.processSequences(this._isFirstFrame);
  }, CVShapeElement.prototype.addTransformToStyleList = function (t) {
    var e,
        r = this.stylesList.length;

    for (e = 0; e < r; e += 1) {
      this.stylesList[e].closed || this.stylesList[e].transforms.push(t);
    }
  }, CVShapeElement.prototype.removeTransformFromStyleList = function () {
    var t,
        e = this.stylesList.length;

    for (t = 0; t < e; t += 1) {
      this.stylesList[t].closed || this.stylesList[t].transforms.pop();
    }
  }, CVShapeElement.prototype.closeStyles = function (t) {
    var e,
        r = t.length;

    for (e = 0; e < r; e += 1) {
      t[e].closed = !0;
    }
  }, CVShapeElement.prototype.searchShapes = function (t, e, r, i, s) {
    var a,
        n,
        o,
        h,
        l,
        p,
        m = t.length - 1,
        f = [],
        c = [],
        d = [].concat(s);

    for (a = m; 0 <= a; a -= 1) {
      if ((h = this.searchProcessedElement(t[a])) ? e[a] = r[h - 1] : t[a]._shouldRender = i, "fl" === t[a].ty || "st" === t[a].ty || "gf" === t[a].ty || "gs" === t[a].ty) h ? e[a].style.closed = !1 : e[a] = this.createStyleElement(t[a], d), f.push(e[a].style);else if ("gr" === t[a].ty) {
        if (h) for (o = e[a].it.length, n = 0; n < o; n += 1) {
          e[a].prevViewData[n] = e[a].it[n];
        } else e[a] = this.createGroupElement(t[a]);
        this.searchShapes(t[a].it, e[a].it, e[a].prevViewData, i, d);
      } else "tr" === t[a].ty ? (h || (p = this.createTransformElement(t[a]), e[a] = p), d.push(e[a]), this.addTransformToStyleList(e[a])) : "sh" === t[a].ty || "rc" === t[a].ty || "el" === t[a].ty || "sr" === t[a].ty ? h || (e[a] = this.createShapeElement(t[a])) : "tm" === t[a].ty || "rd" === t[a].ty || "pb" === t[a].ty ? (h ? (l = e[a]).closed = !1 : ((l = ShapeModifiers.getModifier(t[a].ty)).init(this, t[a]), e[a] = l, this.shapeModifiers.push(l)), c.push(l)) : "rp" === t[a].ty && (h ? (l = e[a]).closed = !0 : (l = ShapeModifiers.getModifier(t[a].ty), (e[a] = l).init(this, t, a, e), this.shapeModifiers.push(l), i = !1), c.push(l));
      this.addProcessedElement(t[a], a + 1);
    }

    for (this.removeTransformFromStyleList(), this.closeStyles(f), m = c.length, a = 0; a < m; a += 1) {
      c[a].closed = !0;
    }
  }, CVShapeElement.prototype.renderInnerContent = function () {
    this.transformHelper.opacity = 1, this.transformHelper._opMdf = !1, this.renderModifiers(), this.transformsManager.processSequences(this._isFirstFrame), this.renderShape(this.transformHelper, this.shapesData, this.itemsData, !0);
  }, CVShapeElement.prototype.renderShapeTransform = function (t, e) {
    (t._opMdf || e.op._mdf || this._isFirstFrame) && (e.opacity = t.opacity, e.opacity *= e.op.v, e._opMdf = !0);
  }, CVShapeElement.prototype.drawLayer = function () {
    var t,
        e,
        r,
        i,
        s,
        a,
        n,
        o,
        h,
        l = this.stylesList.length,
        p = this.globalData.renderer,
        m = this.globalData.canvasContext;

    for (t = 0; t < l; t += 1) {
      if (("st" !== (o = (h = this.stylesList[t]).type) && "gs" !== o || 0 !== h.wi) && h.data._shouldRender && 0 !== h.coOp && 0 !== this.globalData.currentGlobalAlpha) {
        for (p.save(), a = h.elements, "st" === o || "gs" === o ? (m.strokeStyle = "st" === o ? h.co : h.grd, m.lineWidth = h.wi, m.lineCap = h.lc, m.lineJoin = h.lj, m.miterLimit = h.ml || 0) : m.fillStyle = "fl" === o ? h.co : h.grd, p.ctxOpacity(h.coOp), "st" !== o && "gs" !== o && m.beginPath(), p.ctxTransform(h.preTransforms.finalTransform.props), r = a.length, e = 0; e < r; e += 1) {
          for ("st" !== o && "gs" !== o || (m.beginPath(), h.da && (m.setLineDash(h.da), m.lineDashOffset = h["do"])), s = (n = a[e].trNodes).length, i = 0; i < s; i += 1) {
            "m" === n[i].t ? m.moveTo(n[i].p[0], n[i].p[1]) : "c" === n[i].t ? m.bezierCurveTo(n[i].pts[0], n[i].pts[1], n[i].pts[2], n[i].pts[3], n[i].pts[4], n[i].pts[5]) : m.closePath();
          }

          "st" !== o && "gs" !== o || (m.stroke(), h.da && m.setLineDash(this.dashResetter));
        }

        "st" !== o && "gs" !== o && m.fill(h.r), p.restore();
      }
    }
  }, CVShapeElement.prototype.renderShape = function (t, e, r, i) {
    var s, a;

    for (a = t, s = e.length - 1; 0 <= s; s -= 1) {
      "tr" === e[s].ty ? (a = r[s].transform, this.renderShapeTransform(t, a)) : "sh" === e[s].ty || "el" === e[s].ty || "rc" === e[s].ty || "sr" === e[s].ty ? this.renderPath(e[s], r[s]) : "fl" === e[s].ty ? this.renderFill(e[s], r[s], a) : "st" === e[s].ty ? this.renderStroke(e[s], r[s], a) : "gf" === e[s].ty || "gs" === e[s].ty ? this.renderGradientFill(e[s], r[s], a) : "gr" === e[s].ty ? this.renderShape(a, e[s].it, r[s].it) : e[s].ty;
    }

    i && this.drawLayer();
  }, CVShapeElement.prototype.renderStyledShape = function (t, e) {
    if (this._isFirstFrame || e._mdf || t.transforms._mdf) {
      var r,
          i,
          s,
          a = t.trNodes,
          n = e.paths,
          o = n._length;
      a.length = 0;
      var h = t.transforms.finalTransform;

      for (s = 0; s < o; s += 1) {
        var l = n.shapes[s];

        if (l && l.v) {
          for (i = l._length, r = 1; r < i; r += 1) {
            1 === r && a.push({
              t: "m",
              p: h.applyToPointArray(l.v[0][0], l.v[0][1], 0)
            }), a.push({
              t: "c",
              pts: h.applyToTriplePoints(l.o[r - 1], l.i[r], l.v[r])
            });
          }

          1 === i && a.push({
            t: "m",
            p: h.applyToPointArray(l.v[0][0], l.v[0][1], 0)
          }), l.c && i && (a.push({
            t: "c",
            pts: h.applyToTriplePoints(l.o[r - 1], l.i[0], l.v[0])
          }), a.push({
            t: "z"
          }));
        }
      }

      t.trNodes = a;
    }
  }, CVShapeElement.prototype.renderPath = function (t, e) {
    if (!0 !== t.hd && t._shouldRender) {
      var r,
          i = e.styledShapes.length;

      for (r = 0; r < i; r += 1) {
        this.renderStyledShape(e.styledShapes[r], e.sh);
      }
    }
  }, CVShapeElement.prototype.renderFill = function (t, e, r) {
    var i = e.style;
    (e.c._mdf || this._isFirstFrame) && (i.co = "rgb(" + bmFloor(e.c.v[0]) + "," + bmFloor(e.c.v[1]) + "," + bmFloor(e.c.v[2]) + ")"), (e.o._mdf || r._opMdf || this._isFirstFrame) && (i.coOp = e.o.v * r.opacity);
  }, CVShapeElement.prototype.renderGradientFill = function (t, e, r) {
    var i,
        s = e.style;

    if (!s.grd || e.g._mdf || e.s._mdf || e.e._mdf || 1 !== t.t && (e.h._mdf || e.a._mdf)) {
      var a,
          n = this.globalData.canvasContext,
          o = e.s.v,
          h = e.e.v;
      if (1 === t.t) i = n.createLinearGradient(o[0], o[1], h[0], h[1]);else {
        var l = Math.sqrt(Math.pow(o[0] - h[0], 2) + Math.pow(o[1] - h[1], 2)),
            p = Math.atan2(h[1] - o[1], h[0] - o[0]),
            m = e.h.v;
        1 <= m ? m = .99 : m <= -1 && (m = -.99);
        var f = l * m,
            c = Math.cos(p + e.a.v) * f + o[0],
            d = Math.sin(p + e.a.v) * f + o[1];
        i = n.createRadialGradient(c, d, 0, o[0], o[1], l);
      }
      var u = t.g.p,
          y = e.g.c,
          g = 1;

      for (a = 0; a < u; a += 1) {
        e.g._hasOpacity && e.g._collapsable && (g = e.g.o[2 * a + 1]), i.addColorStop(y[4 * a] / 100, "rgba(" + y[4 * a + 1] + "," + y[4 * a + 2] + "," + y[4 * a + 3] + "," + g + ")");
      }

      s.grd = i;
    }

    s.coOp = e.o.v * r.opacity;
  }, CVShapeElement.prototype.renderStroke = function (t, e, r) {
    var i = e.style,
        s = e.d;
    s && (s._mdf || this._isFirstFrame) && (i.da = s.dashArray, i["do"] = s.dashoffset[0]), (e.c._mdf || this._isFirstFrame) && (i.co = "rgb(" + bmFloor(e.c.v[0]) + "," + bmFloor(e.c.v[1]) + "," + bmFloor(e.c.v[2]) + ")"), (e.o._mdf || r._opMdf || this._isFirstFrame) && (i.coOp = e.o.v * r.opacity), (e.w._mdf || this._isFirstFrame) && (i.wi = e.w.v);
  }, CVShapeElement.prototype.destroy = function () {
    this.shapesData = null, this.globalData = null, this.canvasContext = null, this.stylesList.length = 0, this.itemsData.length = 0;
  }, extendPrototype([BaseElement, TransformElement, CVBaseElement, HierarchyElement, FrameElement, RenderableElement], CVSolidElement), CVSolidElement.prototype.initElement = SVGShapeElement.prototype.initElement, CVSolidElement.prototype.prepareFrame = IImageElement.prototype.prepareFrame, CVSolidElement.prototype.renderInnerContent = function () {
    var t = this.canvasContext;
    t.fillStyle = this.data.sc, t.fillRect(0, 0, this.data.sw, this.data.sh);
  }, extendPrototype([BaseElement, TransformElement, CVBaseElement, HierarchyElement, FrameElement, RenderableElement, ITextElement], CVTextElement), CVTextElement.prototype.tHelper = createTag("canvas").getContext("2d"), CVTextElement.prototype.buildNewText = function () {
    var t = this.textProperty.currentData;
    this.renderedLetters = createSizedArray(t.l ? t.l.length : 0);
    var e = !1;
    t.fc ? (e = !0, this.values.fill = this.buildColor(t.fc)) : this.values.fill = "rgba(0,0,0,0)", this.fill = e;
    var r = !1;
    t.sc && (r = !0, this.values.stroke = this.buildColor(t.sc), this.values.sWidth = t.sw);
    var i,
        s,
        a,
        n,
        o,
        h,
        l,
        p,
        m,
        f,
        c,
        d,
        u = this.globalData.fontManager.getFontByName(t.f),
        y = t.l,
        g = this.mHelper;
    this.stroke = r, this.values.fValue = t.finalSize + "px " + this.globalData.fontManager.getFontByName(t.f).fFamily, s = t.finalText.length;
    var v = this.data.singleShape,
        b = .001 * t.tr * t.finalSize,
        P = 0,
        E = 0,
        x = !0,
        S = 0;

    for (i = 0; i < s; i += 1) {
      for (n = (a = this.globalData.fontManager.getCharData(t.finalText[i], u.fStyle, this.globalData.fontManager.getFontByName(t.f).fFamily)) && a.data || {}, g.reset(), v && y[i].n && (P = -b, E += t.yOffset, E += x ? 1 : 0, x = !1), m = (l = n.shapes ? n.shapes[0].it : []).length, g.scale(t.finalSize / 100, t.finalSize / 100), v && this.applyTextPropertiesToMatrix(t, g, y[i].line, P, E), c = createSizedArray(m), p = 0; p < m; p += 1) {
        for (h = l[p].ks.k.i.length, f = l[p].ks.k, d = [], o = 1; o < h; o += 1) {
          1 === o && d.push(g.applyToX(f.v[0][0], f.v[0][1], 0), g.applyToY(f.v[0][0], f.v[0][1], 0)), d.push(g.applyToX(f.o[o - 1][0], f.o[o - 1][1], 0), g.applyToY(f.o[o - 1][0], f.o[o - 1][1], 0), g.applyToX(f.i[o][0], f.i[o][1], 0), g.applyToY(f.i[o][0], f.i[o][1], 0), g.applyToX(f.v[o][0], f.v[o][1], 0), g.applyToY(f.v[o][0], f.v[o][1], 0));
        }

        d.push(g.applyToX(f.o[o - 1][0], f.o[o - 1][1], 0), g.applyToY(f.o[o - 1][0], f.o[o - 1][1], 0), g.applyToX(f.i[0][0], f.i[0][1], 0), g.applyToY(f.i[0][0], f.i[0][1], 0), g.applyToX(f.v[0][0], f.v[0][1], 0), g.applyToY(f.v[0][0], f.v[0][1], 0)), c[p] = d;
      }

      v && (P += y[i].l, P += b), this.textSpans[S] ? this.textSpans[S].elem = c : this.textSpans[S] = {
        elem: c
      }, S += 1;
    }
  }, CVTextElement.prototype.renderInnerContent = function () {
    var t,
        e,
        r,
        i,
        s,
        a,
        n = this.canvasContext;
    n.font = this.values.fValue, n.lineCap = "butt", n.lineJoin = "miter", n.miterLimit = 4, this.data.singleShape || this.textAnimator.getMeasures(this.textProperty.currentData, this.lettersChangedFlag);
    var o,
        h = this.textAnimator.renderedLetters,
        l = this.textProperty.currentData.l;
    e = l.length;
    var p,
        m,
        f = null,
        c = null,
        d = null;

    for (t = 0; t < e; t += 1) {
      if (!l[t].n) {
        if ((o = h[t]) && (this.globalData.renderer.save(), this.globalData.renderer.ctxTransform(o.p), this.globalData.renderer.ctxOpacity(o.o)), this.fill) {
          for (o && o.fc ? f !== o.fc && (f = o.fc, n.fillStyle = o.fc) : f !== this.values.fill && (f = this.values.fill, n.fillStyle = this.values.fill), i = (p = this.textSpans[t].elem).length, this.globalData.canvasContext.beginPath(), r = 0; r < i; r += 1) {
            for (a = (m = p[r]).length, this.globalData.canvasContext.moveTo(m[0], m[1]), s = 2; s < a; s += 6) {
              this.globalData.canvasContext.bezierCurveTo(m[s], m[s + 1], m[s + 2], m[s + 3], m[s + 4], m[s + 5]);
            }
          }

          this.globalData.canvasContext.closePath(), this.globalData.canvasContext.fill();
        }

        if (this.stroke) {
          for (o && o.sw ? d !== o.sw && (d = o.sw, n.lineWidth = o.sw) : d !== this.values.sWidth && (d = this.values.sWidth, n.lineWidth = this.values.sWidth), o && o.sc ? c !== o.sc && (c = o.sc, n.strokeStyle = o.sc) : c !== this.values.stroke && (c = this.values.stroke, n.strokeStyle = this.values.stroke), i = (p = this.textSpans[t].elem).length, this.globalData.canvasContext.beginPath(), r = 0; r < i; r += 1) {
            for (a = (m = p[r]).length, this.globalData.canvasContext.moveTo(m[0], m[1]), s = 2; s < a; s += 6) {
              this.globalData.canvasContext.bezierCurveTo(m[s], m[s + 1], m[s + 2], m[s + 3], m[s + 4], m[s + 5]);
            }
          }

          this.globalData.canvasContext.closePath(), this.globalData.canvasContext.stroke();
        }

        o && this.globalData.renderer.restore();
      }
    }
  }, CVEffects.prototype.renderFrame = function () {}, HBaseElement.prototype = {
    checkBlendMode: function checkBlendMode() {},
    initRendererElement: function initRendererElement() {
      this.baseElement = createTag(this.data.tg || "div"), this.data.hasMask ? (this.svgElement = createNS("svg"), this.layerElement = createNS("g"), this.maskedElement = this.layerElement, this.svgElement.appendChild(this.layerElement), this.baseElement.appendChild(this.svgElement)) : this.layerElement = this.baseElement, styleDiv(this.baseElement);
    },
    createContainerElements: function createContainerElements() {
      this.renderableEffectsManager = new CVEffects(this), this.transformedElement = this.baseElement, this.maskedElement = this.layerElement, this.data.ln && this.layerElement.setAttribute("id", this.data.ln), this.data.cl && this.layerElement.setAttribute("class", this.data.cl), 0 !== this.data.bm && this.setBlendMode();
    },
    renderElement: function renderElement() {
      var t = this.transformedElement ? this.transformedElement.style : {};

      if (this.finalTransform._matMdf) {
        var e = this.finalTransform.mat.toCSS();
        t.transform = e, t.webkitTransform = e;
      }

      this.finalTransform._opMdf && (t.opacity = this.finalTransform.mProp.o.v);
    },
    renderFrame: function renderFrame() {
      this.data.hd || this.hidden || (this.renderTransform(), this.renderRenderable(), this.renderElement(), this.renderInnerContent(), this._isFirstFrame && (this._isFirstFrame = !1));
    },
    destroy: function destroy() {
      this.layerElement = null, this.transformedElement = null, this.matteElement && (this.matteElement = null), this.maskManager && (this.maskManager.destroy(), this.maskManager = null);
    },
    createRenderableComponents: function createRenderableComponents() {
      this.maskManager = new MaskElement(this.data, this, this.globalData);
    },
    addEffects: function addEffects() {},
    setMatte: function setMatte() {}
  }, HBaseElement.prototype.getBaseElement = SVGBaseElement.prototype.getBaseElement, HBaseElement.prototype.destroyBaseElement = HBaseElement.prototype.destroy, HBaseElement.prototype.buildElementParenting = HybridRenderer.prototype.buildElementParenting, extendPrototype([BaseElement, TransformElement, HBaseElement, HierarchyElement, FrameElement, RenderableDOMElement], HSolidElement), HSolidElement.prototype.createContent = function () {
    var t;
    this.data.hasMask ? ((t = createNS("rect")).setAttribute("width", this.data.sw), t.setAttribute("height", this.data.sh), t.setAttribute("fill", this.data.sc), this.svgElement.setAttribute("width", this.data.sw), this.svgElement.setAttribute("height", this.data.sh)) : ((t = createTag("div")).style.width = this.data.sw + "px", t.style.height = this.data.sh + "px", t.style.backgroundColor = this.data.sc), this.layerElement.appendChild(t);
  }, extendPrototype([HybridRenderer, ICompElement, HBaseElement], HCompElement), HCompElement.prototype._createBaseContainerElements = HCompElement.prototype.createContainerElements, HCompElement.prototype.createContainerElements = function () {
    this._createBaseContainerElements(), this.data.hasMask ? (this.svgElement.setAttribute("width", this.data.w), this.svgElement.setAttribute("height", this.data.h), this.transformedElement = this.baseElement) : this.transformedElement = this.layerElement;
  }, HCompElement.prototype.addTo3dContainer = function (t, e) {
    for (var r, i = 0; i < e;) {
      this.elements[i] && this.elements[i].getBaseElement && (r = this.elements[i].getBaseElement()), i += 1;
    }

    r ? this.layerElement.insertBefore(t, r) : this.layerElement.appendChild(t);
  }, extendPrototype([BaseElement, TransformElement, HSolidElement, SVGShapeElement, HBaseElement, HierarchyElement, FrameElement, RenderableElement], HShapeElement), HShapeElement.prototype._renderShapeFrame = HShapeElement.prototype.renderInnerContent, HShapeElement.prototype.createContent = function () {
    var t;
    if (this.baseElement.style.fontSize = 0, this.data.hasMask) this.layerElement.appendChild(this.shapesContainer), t = this.svgElement;else {
      t = createNS("svg");
      var e = this.comp.data ? this.comp.data : this.globalData.compSize;
      t.setAttribute("width", e.w), t.setAttribute("height", e.h), t.appendChild(this.shapesContainer), this.layerElement.appendChild(t);
    }
    this.searchShapes(this.shapesData, this.itemsData, this.prevViewData, this.shapesContainer, 0, [], !0), this.filterUniqueShapes(), this.shapeCont = t;
  }, HShapeElement.prototype.getTransformedPoint = function (t, e) {
    var r,
        i = t.length;

    for (r = 0; r < i; r += 1) {
      e = t[r].mProps.v.applyToPointArray(e[0], e[1], 0);
    }

    return e;
  }, HShapeElement.prototype.calculateShapeBoundingBox = function (t, e) {
    var r,
        i,
        s,
        a,
        n,
        o = t.sh.v,
        h = t.transformers,
        l = o._length;

    if (!(l <= 1)) {
      for (r = 0; r < l - 1; r += 1) {
        i = this.getTransformedPoint(h, o.v[r]), s = this.getTransformedPoint(h, o.o[r]), a = this.getTransformedPoint(h, o.i[r + 1]), n = this.getTransformedPoint(h, o.v[r + 1]), this.checkBounds(i, s, a, n, e);
      }

      o.c && (i = this.getTransformedPoint(h, o.v[r]), s = this.getTransformedPoint(h, o.o[r]), a = this.getTransformedPoint(h, o.i[0]), n = this.getTransformedPoint(h, o.v[0]), this.checkBounds(i, s, a, n, e));
    }
  }, HShapeElement.prototype.checkBounds = function (t, e, r, i, s) {
    this.getBoundsOfCurve(t, e, r, i);
    var a = this.shapeBoundingBox;
    s.x = bmMin(a.left, s.x), s.xMax = bmMax(a.right, s.xMax), s.y = bmMin(a.top, s.y), s.yMax = bmMax(a.bottom, s.yMax);
  }, HShapeElement.prototype.shapeBoundingBox = {
    left: 0,
    right: 0,
    top: 0,
    bottom: 0
  }, HShapeElement.prototype.tempBoundingBox = {
    x: 0,
    xMax: 0,
    y: 0,
    yMax: 0,
    width: 0,
    height: 0
  }, HShapeElement.prototype.getBoundsOfCurve = function (t, e, r, i) {
    for (var s, a, n, o, h, l, p, m = [[t[0], i[0]], [t[1], i[1]]], f = 0; f < 2; ++f) {
      a = 6 * t[f] - 12 * e[f] + 6 * r[f], s = -3 * t[f] + 9 * e[f] - 9 * r[f] + 3 * i[f], n = 3 * e[f] - 3 * t[f], a |= 0, n |= 0, 0 === (s |= 0) && 0 === a || (0 === s ? 0 < (o = -n / a) && o < 1 && m[f].push(this.calculateF(o, t, e, r, i, f)) : 0 <= (h = a * a - 4 * n * s) && (0 < (l = (-a + bmSqrt(h)) / (2 * s)) && l < 1 && m[f].push(this.calculateF(l, t, e, r, i, f)), 0 < (p = (-a - bmSqrt(h)) / (2 * s)) && p < 1 && m[f].push(this.calculateF(p, t, e, r, i, f))));
    }

    this.shapeBoundingBox.left = bmMin.apply(null, m[0]), this.shapeBoundingBox.top = bmMin.apply(null, m[1]), this.shapeBoundingBox.right = bmMax.apply(null, m[0]), this.shapeBoundingBox.bottom = bmMax.apply(null, m[1]);
  }, HShapeElement.prototype.calculateF = function (t, e, r, i, s, a) {
    return bmPow(1 - t, 3) * e[a] + 3 * bmPow(1 - t, 2) * t * r[a] + 3 * (1 - t) * bmPow(t, 2) * i[a] + bmPow(t, 3) * s[a];
  }, HShapeElement.prototype.calculateBoundingBox = function (t, e) {
    var r,
        i = t.length;

    for (r = 0; r < i; r += 1) {
      t[r] && t[r].sh ? this.calculateShapeBoundingBox(t[r], e) : t[r] && t[r].it && this.calculateBoundingBox(t[r].it, e);
    }
  }, HShapeElement.prototype.currentBoxContains = function (t) {
    return this.currentBBox.x <= t.x && this.currentBBox.y <= t.y && this.currentBBox.width + this.currentBBox.x >= t.x + t.width && this.currentBBox.height + this.currentBBox.y >= t.y + t.height;
  }, HShapeElement.prototype.renderInnerContent = function () {
    if (this._renderShapeFrame(), !this.hidden && (this._isFirstFrame || this._mdf)) {
      var t = this.tempBoundingBox,
          e = 999999;
      if (t.x = e, t.xMax = -e, t.y = e, t.yMax = -e, this.calculateBoundingBox(this.itemsData, t), t.width = t.xMax < t.x ? 0 : t.xMax - t.x, t.height = t.yMax < t.y ? 0 : t.yMax - t.y, this.currentBoxContains(t)) return;
      var r = !1;

      if (this.currentBBox.w !== t.width && (this.currentBBox.w = t.width, this.shapeCont.setAttribute("width", t.width), r = !0), this.currentBBox.h !== t.height && (this.currentBBox.h = t.height, this.shapeCont.setAttribute("height", t.height), r = !0), r || this.currentBBox.x !== t.x || this.currentBBox.y !== t.y) {
        this.currentBBox.w = t.width, this.currentBBox.h = t.height, this.currentBBox.x = t.x, this.currentBBox.y = t.y, this.shapeCont.setAttribute("viewBox", this.currentBBox.x + " " + this.currentBBox.y + " " + this.currentBBox.w + " " + this.currentBBox.h);
        var i = this.shapeCont.style,
            s = "translate(" + this.currentBBox.x + "px," + this.currentBBox.y + "px)";
        i.transform = s, i.webkitTransform = s;
      }
    }
  }, extendPrototype([BaseElement, TransformElement, HBaseElement, HierarchyElement, FrameElement, RenderableDOMElement, ITextElement], HTextElement), HTextElement.prototype.createContent = function () {
    if (this.isMasked = this.checkMasks(), this.isMasked) {
      this.renderType = "svg", this.compW = this.comp.data.w, this.compH = this.comp.data.h, this.svgElement.setAttribute("width", this.compW), this.svgElement.setAttribute("height", this.compH);
      var t = createNS("g");
      this.maskedElement.appendChild(t), this.innerElem = t;
    } else this.renderType = "html", this.innerElem = this.layerElement;

    this.checkParenting();
  }, HTextElement.prototype.buildNewText = function () {
    var t = this.textProperty.currentData;
    this.renderedLetters = createSizedArray(t.l ? t.l.length : 0);
    var e = this.innerElem.style,
        r = t.fc ? this.buildColor(t.fc) : "rgba(0,0,0,0)";
    e.fill = r, e.color = r, t.sc && (e.stroke = this.buildColor(t.sc), e.strokeWidth = t.sw + "px");
    var i,
        s,
        a = this.globalData.fontManager.getFontByName(t.f);
    if (!this.globalData.fontManager.chars) if (e.fontSize = t.finalSize + "px", e.lineHeight = t.finalSize + "px", a.fClass) this.innerElem.className = a.fClass;else {
      e.fontFamily = a.fFamily;
      var n = t.fWeight,
          o = t.fStyle;
      e.fontStyle = o, e.fontWeight = n;
    }
    var h,
        l,
        p,
        m = t.l;
    s = m.length;
    var f,
        c = this.mHelper,
        d = "",
        u = 0;

    for (i = 0; i < s; i += 1) {
      if (this.globalData.fontManager.chars ? (this.textPaths[u] ? h = this.textPaths[u] : ((h = createNS("path")).setAttribute("stroke-linecap", lineCapEnum[1]), h.setAttribute("stroke-linejoin", lineJoinEnum[2]), h.setAttribute("stroke-miterlimit", "4")), this.isMasked || (this.textSpans[u] ? p = (l = this.textSpans[u]).children[0] : ((l = createTag("div")).style.lineHeight = 0, (p = createNS("svg")).appendChild(h), styleDiv(l)))) : this.isMasked ? h = this.textPaths[u] ? this.textPaths[u] : createNS("text") : this.textSpans[u] ? (l = this.textSpans[u], h = this.textPaths[u]) : (styleDiv(l = createTag("span")), styleDiv(h = createTag("span")), l.appendChild(h)), this.globalData.fontManager.chars) {
        var y,
            g = this.globalData.fontManager.getCharData(t.finalText[i], a.fStyle, this.globalData.fontManager.getFontByName(t.f).fFamily);
        if (y = g ? g.data : null, c.reset(), y && y.shapes && (f = y.shapes[0].it, c.scale(t.finalSize / 100, t.finalSize / 100), d = this.createPathShape(c, f), h.setAttribute("d", d)), this.isMasked) this.innerElem.appendChild(h);else {
          if (this.innerElem.appendChild(l), y && y.shapes) {
            document.body.appendChild(p);
            var v = p.getBBox();
            p.setAttribute("width", v.width + 2), p.setAttribute("height", v.height + 2), p.setAttribute("viewBox", v.x - 1 + " " + (v.y - 1) + " " + (v.width + 2) + " " + (v.height + 2));
            var b = p.style,
                P = "translate(" + (v.x - 1) + "px," + (v.y - 1) + "px)";
            b.transform = P, b.webkitTransform = P, m[i].yOffset = v.y - 1;
          } else p.setAttribute("width", 1), p.setAttribute("height", 1);

          l.appendChild(p);
        }
      } else if (h.textContent = m[i].val, h.setAttributeNS("http://www.w3.org/XML/1998/namespace", "xml:space", "preserve"), this.isMasked) this.innerElem.appendChild(h);else {
        this.innerElem.appendChild(l);
        var E = h.style,
            x = "translate3d(0," + -t.finalSize / 1.2 + "px,0)";
        E.transform = x, E.webkitTransform = x;
      }

      this.isMasked ? this.textSpans[u] = h : this.textSpans[u] = l, this.textSpans[u].style.display = "block", this.textPaths[u] = h, u += 1;
    }

    for (; u < this.textSpans.length;) {
      this.textSpans[u].style.display = "none", u += 1;
    }
  }, HTextElement.prototype.renderInnerContent = function () {
    var t;

    if (this.data.singleShape) {
      if (!this._isFirstFrame && !this.lettersChangedFlag) return;

      if (this.isMasked && this.finalTransform._matMdf) {
        this.svgElement.setAttribute("viewBox", -this.finalTransform.mProp.p.v[0] + " " + -this.finalTransform.mProp.p.v[1] + " " + this.compW + " " + this.compH), t = this.svgElement.style;
        var e = "translate(" + -this.finalTransform.mProp.p.v[0] + "px," + -this.finalTransform.mProp.p.v[1] + "px)";
        t.transform = e, t.webkitTransform = e;
      }
    }

    if (this.textAnimator.getMeasures(this.textProperty.currentData, this.lettersChangedFlag), this.lettersChangedFlag || this.textAnimator.lettersChangedFlag) {
      var r,
          i,
          s,
          a,
          n,
          o = 0,
          h = this.textAnimator.renderedLetters,
          l = this.textProperty.currentData.l;

      for (i = l.length, r = 0; r < i; r += 1) {
        l[r].n ? o += 1 : (a = this.textSpans[r], n = this.textPaths[r], s = h[o], o += 1, s._mdf.m && (this.isMasked ? a.setAttribute("transform", s.m) : (a.style.webkitTransform = s.m, a.style.transform = s.m)), a.style.opacity = s.o, s.sw && s._mdf.sw && n.setAttribute("stroke-width", s.sw), s.sc && s._mdf.sc && n.setAttribute("stroke", s.sc), s.fc && s._mdf.fc && (n.setAttribute("fill", s.fc), n.style.color = s.fc));
      }

      if (this.innerElem.getBBox && !this.hidden && (this._isFirstFrame || this._mdf)) {
        var p = this.innerElem.getBBox();
        this.currentBBox.w !== p.width && (this.currentBBox.w = p.width, this.svgElement.setAttribute("width", p.width)), this.currentBBox.h !== p.height && (this.currentBBox.h = p.height, this.svgElement.setAttribute("height", p.height));

        if (this.currentBBox.w !== p.width + 2 || this.currentBBox.h !== p.height + 2 || this.currentBBox.x !== p.x - 1 || this.currentBBox.y !== p.y - 1) {
          this.currentBBox.w = p.width + 2, this.currentBBox.h = p.height + 2, this.currentBBox.x = p.x - 1, this.currentBBox.y = p.y - 1, this.svgElement.setAttribute("viewBox", this.currentBBox.x + " " + this.currentBBox.y + " " + this.currentBBox.w + " " + this.currentBBox.h), t = this.svgElement.style;
          var m = "translate(" + this.currentBBox.x + "px," + this.currentBBox.y + "px)";
          t.transform = m, t.webkitTransform = m;
        }
      }
    }
  }, extendPrototype([BaseElement, TransformElement, HBaseElement, HSolidElement, HierarchyElement, FrameElement, RenderableElement], HImageElement), HImageElement.prototype.createContent = function () {
    var t = this.globalData.getAssetsPath(this.assetData),
        e = new Image();
    this.data.hasMask ? (this.imageElem = createNS("image"), this.imageElem.setAttribute("width", this.assetData.w + "px"), this.imageElem.setAttribute("height", this.assetData.h + "px"), this.imageElem.setAttributeNS("http://www.w3.org/1999/xlink", "href", t), this.layerElement.appendChild(this.imageElem), this.baseElement.setAttribute("width", this.assetData.w), this.baseElement.setAttribute("height", this.assetData.h)) : this.layerElement.appendChild(e), e.crossOrigin = "anonymous", e.src = t, this.data.ln && this.baseElement.setAttribute("id", this.data.ln);
  }, extendPrototype([BaseElement, FrameElement, HierarchyElement], HCameraElement), HCameraElement.prototype.setup = function () {
    var t,
        e,
        r,
        i,
        s = this.comp.threeDElements.length;

    for (t = 0; t < s; t += 1) {
      if ("3d" === (e = this.comp.threeDElements[t]).type) {
        r = e.perspectiveElem.style, i = e.container.style;
        var a = this.pe.v + "px",
            n = "0px 0px 0px",
            o = "matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1)";
        r.perspective = a, r.webkitPerspective = a, i.transformOrigin = n, i.mozTransformOrigin = n, i.webkitTransformOrigin = n, r.transform = o, r.webkitTransform = o;
      }
    }
  }, HCameraElement.prototype.createElements = function () {}, HCameraElement.prototype.hide = function () {}, HCameraElement.prototype.renderFrame = function () {
    var t,
        e,
        r = this._isFirstFrame;
    if (this.hierarchy) for (e = this.hierarchy.length, t = 0; t < e; t += 1) {
      r = this.hierarchy[t].finalTransform.mProp._mdf || r;
    }

    if (r || this.pe._mdf || this.p && this.p._mdf || this.px && (this.px._mdf || this.py._mdf || this.pz._mdf) || this.rx._mdf || this.ry._mdf || this.rz._mdf || this.or._mdf || this.a && this.a._mdf) {
      if (this.mat.reset(), this.hierarchy) for (t = e = this.hierarchy.length - 1; 0 <= t; t -= 1) {
        var i = this.hierarchy[t].finalTransform.mProp;
        this.mat.translate(-i.p.v[0], -i.p.v[1], i.p.v[2]), this.mat.rotateX(-i.or.v[0]).rotateY(-i.or.v[1]).rotateZ(i.or.v[2]), this.mat.rotateX(-i.rx.v).rotateY(-i.ry.v).rotateZ(i.rz.v), this.mat.scale(1 / i.s.v[0], 1 / i.s.v[1], 1 / i.s.v[2]), this.mat.translate(i.a.v[0], i.a.v[1], i.a.v[2]);
      }

      if (this.p ? this.mat.translate(-this.p.v[0], -this.p.v[1], this.p.v[2]) : this.mat.translate(-this.px.v, -this.py.v, this.pz.v), this.a) {
        var s;
        s = this.p ? [this.p.v[0] - this.a.v[0], this.p.v[1] - this.a.v[1], this.p.v[2] - this.a.v[2]] : [this.px.v - this.a.v[0], this.py.v - this.a.v[1], this.pz.v - this.a.v[2]];
        var a = Math.sqrt(Math.pow(s[0], 2) + Math.pow(s[1], 2) + Math.pow(s[2], 2)),
            n = [s[0] / a, s[1] / a, s[2] / a],
            o = Math.sqrt(n[2] * n[2] + n[0] * n[0]),
            h = Math.atan2(n[1], o),
            l = Math.atan2(n[0], -n[2]);
        this.mat.rotateY(l).rotateX(-h);
      }

      this.mat.rotateX(-this.rx.v).rotateY(-this.ry.v).rotateZ(this.rz.v), this.mat.rotateX(-this.or.v[0]).rotateY(-this.or.v[1]).rotateZ(this.or.v[2]), this.mat.translate(this.globalData.compSize.w / 2, this.globalData.compSize.h / 2, 0), this.mat.translate(0, 0, this.pe.v);
      var p = !this._prevMat.equals(this.mat);

      if ((p || this.pe._mdf) && this.comp.threeDElements) {
        var m, f, c;

        for (e = this.comp.threeDElements.length, t = 0; t < e; t += 1) {
          if ("3d" === (m = this.comp.threeDElements[t]).type) {
            if (p) {
              var d = this.mat.toCSS();
              (c = m.container.style).transform = d, c.webkitTransform = d;
            }

            this.pe._mdf && ((f = m.perspectiveElem.style).perspective = this.pe.v + "px", f.webkitPerspective = this.pe.v + "px");
          }
        }

        this.mat.clone(this._prevMat);
      }
    }

    this._isFirstFrame = !1;
  }, HCameraElement.prototype.prepareFrame = function (t) {
    this.prepareProperties(t, !0);
  }, HCameraElement.prototype.destroy = function () {}, HCameraElement.prototype.getBaseElement = function () {
    return null;
  }, HEffects.prototype.renderFrame = function () {};

  var animationManager = function () {
    var t = {},
        s = [],
        i = 0,
        a = 0,
        n = 0,
        o = !0,
        h = !1;

    function r(t) {
      for (var e = 0, r = t.target; e < a;) {
        s[e].animation === r && (s.splice(e, 1), e -= 1, a -= 1, r.isPaused || m()), e += 1;
      }
    }

    function l(t, e) {
      if (!t) return null;

      for (var r = 0; r < a;) {
        if (s[r].elem === t && null !== s[r].elem) return s[r].animation;
        r += 1;
      }

      var i = new AnimationItem();
      return f(i, t), i.setData(t, e), i;
    }

    function p() {
      n += 1, d();
    }

    function m() {
      n -= 1;
    }

    function f(t, e) {
      t.addEventListener("destroy", r), t.addEventListener("_active", p), t.addEventListener("_idle", m), s.push({
        elem: e,
        animation: t
      }), a += 1;
    }

    function c(t) {
      var e,
          r = t - i;

      for (e = 0; e < a; e += 1) {
        s[e].animation.advanceTime(r);
      }

      i = t, n && !h ? window.requestAnimationFrame(c) : o = !0;
    }

    function e(t) {
      i = t, window.requestAnimationFrame(c);
    }

    function d() {
      !h && n && o && (window.requestAnimationFrame(e), o = !1);
    }

    return t.registerAnimation = l, t.loadAnimation = function (t) {
      var e = new AnimationItem();
      return f(e, null), e.setParams(t), e;
    }, t.setSpeed = function (t, e) {
      var r;

      for (r = 0; r < a; r += 1) {
        s[r].animation.setSpeed(t, e);
      }
    }, t.setDirection = function (t, e) {
      var r;

      for (r = 0; r < a; r += 1) {
        s[r].animation.setDirection(t, e);
      }
    }, t.play = function (t) {
      var e;

      for (e = 0; e < a; e += 1) {
        s[e].animation.play(t);
      }
    }, t.pause = function (t) {
      var e;

      for (e = 0; e < a; e += 1) {
        s[e].animation.pause(t);
      }
    }, t.stop = function (t) {
      var e;

      for (e = 0; e < a; e += 1) {
        s[e].animation.stop(t);
      }
    }, t.togglePause = function (t) {
      var e;

      for (e = 0; e < a; e += 1) {
        s[e].animation.togglePause(t);
      }
    }, t.searchAnimations = function (t, e, r) {
      var i,
          s = [].concat([].slice.call(document.getElementsByClassName("lottie")), [].slice.call(document.getElementsByClassName("bodymovin"))),
          a = s.length;

      for (i = 0; i < a; i += 1) {
        r && s[i].setAttribute("data-bm-type", r), l(s[i], t);
      }

      if (e && 0 === a) {
        r || (r = "svg");
        var n = document.getElementsByTagName("body")[0];
        n.innerText = "";
        var o = createTag("div");
        o.style.width = "100%", o.style.height = "100%", o.setAttribute("data-bm-type", r), n.appendChild(o), l(o, t);
      }
    }, t.resize = function () {
      var t;

      for (t = 0; t < a; t += 1) {
        s[t].animation.resize();
      }
    }, t.goToAndStop = function (t, e, r) {
      var i;

      for (i = 0; i < a; i += 1) {
        s[i].animation.goToAndStop(t, e, r);
      }
    }, t.destroy = function (t) {
      var e;

      for (e = a - 1; 0 <= e; e -= 1) {
        s[e].animation.destroy(t);
      }
    }, t.freeze = function () {
      h = !0;
    }, t.unfreeze = function () {
      h = !1, d();
    }, t.setVolume = function (t, e) {
      var r;

      for (r = 0; r < a; r += 1) {
        s[r].animation.setVolume(t, e);
      }
    }, t.mute = function (t) {
      var e;

      for (e = 0; e < a; e += 1) {
        s[e].animation.mute(t);
      }
    }, t.unmute = function (t) {
      var e;

      for (e = 0; e < a; e += 1) {
        s[e].animation.unmute(t);
      }
    }, t.getRegisteredAnimations = function () {
      var t,
          e = s.length,
          r = [];

      for (t = 0; t < e; t += 1) {
        r.push(s[t].animation);
      }

      return r;
    }, t;
  }(),
      AnimationItem = function AnimationItem() {
    this._cbs = [], this.name = "", this.path = "", this.isLoaded = !1, this.currentFrame = 0, this.currentRawFrame = 0, this.firstFrame = 0, this.totalFrames = 0, this.frameRate = 0, this.frameMult = 0, this.playSpeed = 1, this.playDirection = 1, this.playCount = 0, this.animationData = {}, this.assets = [], this.isPaused = !0, this.autoplay = !1, this.loop = !0, this.renderer = null, this.animationID = createElementID(), this.assetsPath = "", this.timeCompleted = 0, this.segmentPos = 0, this.isSubframeEnabled = subframeEnabled, this.segments = [], this._idle = !0, this._completedLoop = !1, this.projectInterface = ProjectInterface(), this.imagePreloader = new ImagePreloader(), this.audioController = audioControllerFactory(), this.markers = [];
  };

  extendPrototype([BaseEvent], AnimationItem), AnimationItem.prototype.setParams = function (t) {
    (t.wrapper || t.container) && (this.wrapper = t.wrapper || t.container);
    var e = "svg";

    switch (t.animType ? e = t.animType : t.renderer && (e = t.renderer), e) {
      case "canvas":
        this.renderer = new CanvasRenderer(this, t.rendererSettings);
        break;

      case "svg":
        this.renderer = new SVGRenderer(this, t.rendererSettings);
        break;

      default:
        this.renderer = new HybridRenderer(this, t.rendererSettings);
    }

    this.imagePreloader.setCacheType(e, this.renderer.globalData.defs), this.renderer.setProjectInterface(this.projectInterface), this.animType = e, "" === t.loop || null === t.loop || void 0 === t.loop || !0 === t.loop ? this.loop = !0 : !1 === t.loop ? this.loop = !1 : this.loop = parseInt(t.loop, 10), this.autoplay = !("autoplay" in t) || t.autoplay, this.name = t.name ? t.name : "", this.autoloadSegments = !Object.prototype.hasOwnProperty.call(t, "autoloadSegments") || t.autoloadSegments, this.assetsPath = t.assetsPath, this.initialSegment = t.initialSegment, t.audioFactory && this.audioController.setAudioFactory(t.audioFactory), t.animationData ? this.configAnimation(t.animationData) : t.path && (-1 !== t.path.lastIndexOf("\\") ? this.path = t.path.substr(0, t.path.lastIndexOf("\\") + 1) : this.path = t.path.substr(0, t.path.lastIndexOf("/") + 1), this.fileName = t.path.substr(t.path.lastIndexOf("/") + 1), this.fileName = this.fileName.substr(0, this.fileName.lastIndexOf(".json")), assetLoader.load(t.path, this.configAnimation.bind(this), function () {
      this.trigger("data_failed");
    }.bind(this)));
  }, AnimationItem.prototype.setData = function (t, e) {
    e && "object" != _typeof(e) && (e = JSON.parse(e));
    var r = {
      wrapper: t,
      animationData: e
    },
        i = t.attributes;
    r.path = i.getNamedItem("data-animation-path") ? i.getNamedItem("data-animation-path").value : i.getNamedItem("data-bm-path") ? i.getNamedItem("data-bm-path").value : i.getNamedItem("bm-path") ? i.getNamedItem("bm-path").value : "", r.animType = i.getNamedItem("data-anim-type") ? i.getNamedItem("data-anim-type").value : i.getNamedItem("data-bm-type") ? i.getNamedItem("data-bm-type").value : i.getNamedItem("bm-type") ? i.getNamedItem("bm-type").value : i.getNamedItem("data-bm-renderer") ? i.getNamedItem("data-bm-renderer").value : i.getNamedItem("bm-renderer") ? i.getNamedItem("bm-renderer").value : "canvas";
    var s = i.getNamedItem("data-anim-loop") ? i.getNamedItem("data-anim-loop").value : i.getNamedItem("data-bm-loop") ? i.getNamedItem("data-bm-loop").value : i.getNamedItem("bm-loop") ? i.getNamedItem("bm-loop").value : "";
    "false" === s ? r.loop = !1 : "true" === s ? r.loop = !0 : "" !== s && (r.loop = parseInt(s, 10));
    var a = i.getNamedItem("data-anim-autoplay") ? i.getNamedItem("data-anim-autoplay").value : i.getNamedItem("data-bm-autoplay") ? i.getNamedItem("data-bm-autoplay").value : !i.getNamedItem("bm-autoplay") || i.getNamedItem("bm-autoplay").value;
    r.autoplay = "false" !== a, r.name = i.getNamedItem("data-name") ? i.getNamedItem("data-name").value : i.getNamedItem("data-bm-name") ? i.getNamedItem("data-bm-name").value : i.getNamedItem("bm-name") ? i.getNamedItem("bm-name").value : "", "false" === (i.getNamedItem("data-anim-prerender") ? i.getNamedItem("data-anim-prerender").value : i.getNamedItem("data-bm-prerender") ? i.getNamedItem("data-bm-prerender").value : i.getNamedItem("bm-prerender") ? i.getNamedItem("bm-prerender").value : "") && (r.prerender = !1), this.setParams(r);
  }, AnimationItem.prototype.includeLayers = function (t) {
    t.op > this.animationData.op && (this.animationData.op = t.op, this.totalFrames = Math.floor(t.op - this.animationData.ip));
    var e,
        r,
        i = this.animationData.layers,
        s = i.length,
        a = t.layers,
        n = a.length;

    for (r = 0; r < n; r += 1) {
      for (e = 0; e < s;) {
        if (i[e].id === a[r].id) {
          i[e] = a[r];
          break;
        }

        e += 1;
      }
    }

    if ((t.chars || t.fonts) && (this.renderer.globalData.fontManager.addChars(t.chars), this.renderer.globalData.fontManager.addFonts(t.fonts, this.renderer.globalData.defs)), t.assets) for (s = t.assets.length, e = 0; e < s; e += 1) {
      this.animationData.assets.push(t.assets[e]);
    }
    this.animationData.__complete = !1, dataManager.completeData(this.animationData, this.renderer.globalData.fontManager), this.renderer.includeLayers(t.layers), expressionsPlugin && expressionsPlugin.initExpressions(this), this.loadNextSegment();
  }, AnimationItem.prototype.loadNextSegment = function () {
    var t = this.animationData.segments;
    if (!t || 0 === t.length || !this.autoloadSegments) return this.trigger("data_ready"), void (this.timeCompleted = this.totalFrames);
    var e = t.shift();
    this.timeCompleted = e.time * this.frameRate;
    var r = this.path + this.fileName + "_" + this.segmentPos + ".json";
    this.segmentPos += 1, assetLoader.load(r, this.includeLayers.bind(this), function () {
      this.trigger("data_failed");
    }.bind(this));
  }, AnimationItem.prototype.loadSegments = function () {
    this.animationData.segments || (this.timeCompleted = this.totalFrames), this.loadNextSegment();
  }, AnimationItem.prototype.imagesLoaded = function () {
    this.trigger("loaded_images"), this.checkLoaded();
  }, AnimationItem.prototype.preloadImages = function () {
    this.imagePreloader.setAssetsPath(this.assetsPath), this.imagePreloader.setPath(this.path), this.imagePreloader.loadAssets(this.animationData.assets, this.imagesLoaded.bind(this));
  }, AnimationItem.prototype.configAnimation = function (t) {
    if (this.renderer) try {
      this.animationData = t, this.initialSegment ? (this.totalFrames = Math.floor(this.initialSegment[1] - this.initialSegment[0]), this.firstFrame = Math.round(this.initialSegment[0])) : (this.totalFrames = Math.floor(this.animationData.op - this.animationData.ip), this.firstFrame = Math.round(this.animationData.ip)), this.renderer.configAnimation(t), t.assets || (t.assets = []), this.assets = this.animationData.assets, this.frameRate = this.animationData.fr, this.frameMult = this.animationData.fr / 1e3, this.renderer.searchExtraCompositions(t.assets), this.markers = markerParser(t.markers || []), this.trigger("config_ready"), this.preloadImages(), this.loadSegments(), this.updaFrameModifier(), this.waitForFontsLoaded(), this.isPaused && this.audioController.pause();
    } catch (t) {
      this.triggerConfigError(t);
    }
  }, AnimationItem.prototype.waitForFontsLoaded = function () {
    this.renderer && (this.renderer.globalData.fontManager.isLoaded ? this.checkLoaded() : setTimeout(this.waitForFontsLoaded.bind(this), 20));
  }, AnimationItem.prototype.checkLoaded = function () {
    !this.isLoaded && this.renderer.globalData.fontManager.isLoaded && (this.imagePreloader.loadedImages() || "canvas" !== this.renderer.rendererType) && this.imagePreloader.loadedFootages() && (this.isLoaded = !0, dataManager.completeData(this.animationData, this.renderer.globalData.fontManager), expressionsPlugin && expressionsPlugin.initExpressions(this), this.renderer.initItems(), setTimeout(function () {
      this.trigger("DOMLoaded");
    }.bind(this), 0), this.gotoFrame(), this.autoplay && this.play());
  }, AnimationItem.prototype.resize = function () {
    this.renderer.updateContainerSize();
  }, AnimationItem.prototype.setSubframe = function (t) {
    this.isSubframeEnabled = !!t;
  }, AnimationItem.prototype.gotoFrame = function () {
    this.currentFrame = this.isSubframeEnabled ? this.currentRawFrame : ~~this.currentRawFrame, this.timeCompleted !== this.totalFrames && this.currentFrame > this.timeCompleted && (this.currentFrame = this.timeCompleted), this.trigger("enterFrame"), this.renderFrame();
  }, AnimationItem.prototype.renderFrame = function () {
    if (!1 !== this.isLoaded && this.renderer) try {
      this.renderer.renderFrame(this.currentFrame + this.firstFrame);
    } catch (t) {
      this.triggerRenderFrameError(t);
    }
  }, AnimationItem.prototype.play = function (t) {
    t && this.name !== t || !0 === this.isPaused && (this.isPaused = !1, this.audioController.resume(), this._idle && (this._idle = !1, this.trigger("_active")));
  }, AnimationItem.prototype.pause = function (t) {
    t && this.name !== t || !1 === this.isPaused && (this.isPaused = !0, this._idle = !0, this.trigger("_idle"), this.audioController.pause());
  }, AnimationItem.prototype.togglePause = function (t) {
    t && this.name !== t || (!0 === this.isPaused ? this.play() : this.pause());
  }, AnimationItem.prototype.stop = function (t) {
    t && this.name !== t || (this.pause(), this.playCount = 0, this._completedLoop = !1, this.setCurrentRawFrameValue(0));
  }, AnimationItem.prototype.getMarkerData = function (t) {
    for (var e, r = 0; r < this.markers.length; r += 1) {
      if ((e = this.markers[r]).payload && e.payload.name === t) return e;
    }

    return null;
  }, AnimationItem.prototype.goToAndStop = function (t, e, r) {
    if (!r || this.name === r) {
      var i = Number(t);

      if (isNaN(i)) {
        var s = this.getMarkerData(t);
        s && this.goToAndStop(s.time, !0);
      } else e ? this.setCurrentRawFrameValue(t) : this.setCurrentRawFrameValue(t * this.frameModifier);

      this.pause();
    }
  }, AnimationItem.prototype.goToAndPlay = function (t, e, r) {
    if (!r || this.name === r) {
      var i = Number(t);

      if (isNaN(i)) {
        var s = this.getMarkerData(t);
        s && (s.duration ? this.playSegments([s.time, s.time + s.duration], !0) : this.goToAndStop(s.time, !0));
      } else this.goToAndStop(i, e, r);

      this.play();
    }
  }, AnimationItem.prototype.advanceTime = function (t) {
    if (!0 !== this.isPaused && !1 !== this.isLoaded) {
      var e = this.currentRawFrame + t * this.frameModifier,
          r = !1;
      e >= this.totalFrames - 1 && 0 < this.frameModifier ? this.loop && this.playCount !== this.loop ? e >= this.totalFrames ? (this.playCount += 1, this.checkSegments(e % this.totalFrames) || (this.setCurrentRawFrameValue(e % this.totalFrames), this._completedLoop = !0, this.trigger("loopComplete"))) : this.setCurrentRawFrameValue(e) : this.checkSegments(e > this.totalFrames ? e % this.totalFrames : 0) || (r = !0, e = this.totalFrames - 1) : e < 0 ? this.checkSegments(e % this.totalFrames) || (!this.loop || this.playCount-- <= 0 && !0 !== this.loop ? (r = !0, e = 0) : (this.setCurrentRawFrameValue(this.totalFrames + e % this.totalFrames), this._completedLoop ? this.trigger("loopComplete") : this._completedLoop = !0)) : this.setCurrentRawFrameValue(e), r && (this.setCurrentRawFrameValue(e), this.pause(), this.trigger("complete"));
    }
  }, AnimationItem.prototype.adjustSegment = function (t, e) {
    this.playCount = 0, t[1] < t[0] ? (0 < this.frameModifier && (this.playSpeed < 0 ? this.setSpeed(-this.playSpeed) : this.setDirection(-1)), this.totalFrames = t[0] - t[1], this.timeCompleted = this.totalFrames, this.firstFrame = t[1], this.setCurrentRawFrameValue(this.totalFrames - .001 - e)) : t[1] > t[0] && (this.frameModifier < 0 && (this.playSpeed < 0 ? this.setSpeed(-this.playSpeed) : this.setDirection(1)), this.totalFrames = t[1] - t[0], this.timeCompleted = this.totalFrames, this.firstFrame = t[0], this.setCurrentRawFrameValue(.001 + e)), this.trigger("segmentStart");
  }, AnimationItem.prototype.setSegment = function (t, e) {
    var r = -1;
    this.isPaused && (this.currentRawFrame + this.firstFrame < t ? r = t : this.currentRawFrame + this.firstFrame > e && (r = e - t)), this.firstFrame = t, this.totalFrames = e - t, this.timeCompleted = this.totalFrames, -1 !== r && this.goToAndStop(r, !0);
  }, AnimationItem.prototype.playSegments = function (t, e) {
    if (e && (this.segments.length = 0), "object" == _typeof(t[0])) {
      var r,
          i = t.length;

      for (r = 0; r < i; r += 1) {
        this.segments.push(t[r]);
      }
    } else this.segments.push(t);

    this.segments.length && e && this.adjustSegment(this.segments.shift(), 0), this.isPaused && this.play();
  }, AnimationItem.prototype.resetSegments = function (t) {
    this.segments.length = 0, this.segments.push([this.animationData.ip, this.animationData.op]), t && this.checkSegments(0);
  }, AnimationItem.prototype.checkSegments = function (t) {
    return !!this.segments.length && (this.adjustSegment(this.segments.shift(), t), !0);
  }, AnimationItem.prototype.destroy = function (t) {
    t && this.name !== t || !this.renderer || (this.renderer.destroy(), this.imagePreloader.destroy(), this.trigger("destroy"), this._cbs = null, this.onEnterFrame = null, this.onLoopComplete = null, this.onComplete = null, this.onSegmentStart = null, this.onDestroy = null, this.renderer = null, this.renderer = null, this.imagePreloader = null, this.projectInterface = null);
  }, AnimationItem.prototype.setCurrentRawFrameValue = function (t) {
    this.currentRawFrame = t, this.gotoFrame();
  }, AnimationItem.prototype.setSpeed = function (t) {
    this.playSpeed = t, this.updaFrameModifier();
  }, AnimationItem.prototype.setDirection = function (t) {
    this.playDirection = t < 0 ? -1 : 1, this.updaFrameModifier();
  }, AnimationItem.prototype.setVolume = function (t, e) {
    e && this.name !== e || this.audioController.setVolume(t);
  }, AnimationItem.prototype.getVolume = function () {
    return this.audioController.getVolume();
  }, AnimationItem.prototype.mute = function (t) {
    t && this.name !== t || this.audioController.mute();
  }, AnimationItem.prototype.unmute = function (t) {
    t && this.name !== t || this.audioController.unmute();
  }, AnimationItem.prototype.updaFrameModifier = function () {
    this.frameModifier = this.frameMult * this.playSpeed * this.playDirection, this.audioController.setRate(this.playSpeed * this.playDirection);
  }, AnimationItem.prototype.getPath = function () {
    return this.path;
  }, AnimationItem.prototype.getAssetsPath = function (t) {
    var e = "";
    if (t.e) e = t.p;else if (this.assetsPath) {
      var r = t.p;
      -1 !== r.indexOf("images/") && (r = r.split("/")[1]), e = this.assetsPath + r;
    } else e = this.path, e += t.u ? t.u : "", e += t.p;
    return e;
  }, AnimationItem.prototype.getAssetData = function (t) {
    for (var e = 0, r = this.assets.length; e < r;) {
      if (t === this.assets[e].id) return this.assets[e];
      e += 1;
    }

    return null;
  }, AnimationItem.prototype.hide = function () {
    this.renderer.hide();
  }, AnimationItem.prototype.show = function () {
    this.renderer.show();
  }, AnimationItem.prototype.getDuration = function (t) {
    return t ? this.totalFrames : this.totalFrames / this.frameRate;
  }, AnimationItem.prototype.trigger = function (t) {
    if (this._cbs && this._cbs[t]) switch (t) {
      case "enterFrame":
        this.triggerEvent(t, new BMEnterFrameEvent(t, this.currentFrame, this.totalFrames, this.frameModifier));
        break;

      case "loopComplete":
        this.triggerEvent(t, new BMCompleteLoopEvent(t, this.loop, this.playCount, this.frameMult));
        break;

      case "complete":
        this.triggerEvent(t, new BMCompleteEvent(t, this.frameMult));
        break;

      case "segmentStart":
        this.triggerEvent(t, new BMSegmentStartEvent(t, this.firstFrame, this.totalFrames));
        break;

      case "destroy":
        this.triggerEvent(t, new BMDestroyEvent(t, this));
        break;

      default:
        this.triggerEvent(t);
    }
    "enterFrame" === t && this.onEnterFrame && this.onEnterFrame.call(this, new BMEnterFrameEvent(t, this.currentFrame, this.totalFrames, this.frameMult)), "loopComplete" === t && this.onLoopComplete && this.onLoopComplete.call(this, new BMCompleteLoopEvent(t, this.loop, this.playCount, this.frameMult)), "complete" === t && this.onComplete && this.onComplete.call(this, new BMCompleteEvent(t, this.frameMult)), "segmentStart" === t && this.onSegmentStart && this.onSegmentStart.call(this, new BMSegmentStartEvent(t, this.firstFrame, this.totalFrames)), "destroy" === t && this.onDestroy && this.onDestroy.call(this, new BMDestroyEvent(t, this));
  }, AnimationItem.prototype.triggerRenderFrameError = function (t) {
    var e = new BMRenderFrameErrorEvent(t, this.currentFrame);
    this.triggerEvent("error", e), this.onError && this.onError.call(this, e);
  }, AnimationItem.prototype.triggerConfigError = function (t) {
    var e = new BMConfigErrorEvent(t, this.currentFrame);
    this.triggerEvent("error", e), this.onError && this.onError.call(this, e);
  };
  var Expressions = (EY = {}, EY.initExpressions = function (t) {
    var e = 0,
        r = [];

    function i() {
      var t,
          e = r.length;

      for (t = 0; t < e; t += 1) {
        r[t].release();
      }

      r.length = 0;
    }

    t.renderer.compInterface = CompExpressionInterface(t.renderer), t.renderer.globalData.projectInterface.registerComposition(t.renderer), t.renderer.globalData.pushExpression = function () {
      e += 1;
    }, t.renderer.globalData.popExpression = function () {
      0 == (e -= 1) && i();
    }, t.renderer.globalData.registerExpressionProperty = function (t) {
      -1 === r.indexOf(t) && r.push(t);
    };
  }, EY),
      EY;
  expressionsPlugin = Expressions;

  var ExpressionManager = function () {
    var ob = {},
        Math = BMMath,
        window = null,
        document = null,
        XMLHttpRequest = null,
        fetch = null;

    function $bm_isInstanceOfArray(t) {
      return t.constructor === Array || t.constructor === Float32Array;
    }

    function isNumerable(t, e) {
      return "number" === t || "boolean" === t || "string" === t || e instanceof Number;
    }

    function $bm_neg(t) {
      var e = _typeof(t);

      if ("number" === e || "boolean" === e || t instanceof Number) return -t;

      if ($bm_isInstanceOfArray(t)) {
        var r,
            i = t.length,
            s = [];

        for (r = 0; r < i; r += 1) {
          s[r] = -t[r];
        }

        return s;
      }

      return t.propType ? t.v : -t;
    }

    var easeInBez = BezierFactory.getBezierEasing(.333, 0, .833, .833, "easeIn").get,
        easeOutBez = BezierFactory.getBezierEasing(.167, .167, .667, 1, "easeOut").get,
        easeInOutBez = BezierFactory.getBezierEasing(.33, 0, .667, 1, "easeInOut").get;

    function sum(t, e) {
      var r = _typeof(t),
          i = _typeof(e);

      if ("string" === r || "string" === i) return t + e;
      if (isNumerable(r, t) && isNumerable(i, e)) return t + e;
      if ($bm_isInstanceOfArray(t) && isNumerable(i, e)) return (t = t.slice(0))[0] += e, t;
      if (isNumerable(r, t) && $bm_isInstanceOfArray(e)) return (e = e.slice(0))[0] = t + e[0], e;

      if ($bm_isInstanceOfArray(t) && $bm_isInstanceOfArray(e)) {
        for (var s = 0, a = t.length, n = e.length, o = []; s < a || s < n;) {
          ("number" == typeof t[s] || t[s] instanceof Number) && ("number" == typeof e[s] || e[s] instanceof Number) ? o[s] = t[s] + e[s] : o[s] = void 0 === e[s] ? t[s] : t[s] || e[s], s += 1;
        }

        return o;
      }

      return 0;
    }

    var add = sum;

    function sub(t, e) {
      var r = _typeof(t),
          i = _typeof(e);

      if (isNumerable(r, t) && isNumerable(i, e)) return "string" === r && (t = parseInt(t, 10)), "string" === i && (e = parseInt(e, 10)), t - e;
      if ($bm_isInstanceOfArray(t) && isNumerable(i, e)) return (t = t.slice(0))[0] -= e, t;
      if (isNumerable(r, t) && $bm_isInstanceOfArray(e)) return (e = e.slice(0))[0] = t - e[0], e;

      if ($bm_isInstanceOfArray(t) && $bm_isInstanceOfArray(e)) {
        for (var s = 0, a = t.length, n = e.length, o = []; s < a || s < n;) {
          ("number" == typeof t[s] || t[s] instanceof Number) && ("number" == typeof e[s] || e[s] instanceof Number) ? o[s] = t[s] - e[s] : o[s] = void 0 === e[s] ? t[s] : t[s] || e[s], s += 1;
        }

        return o;
      }

      return 0;
    }

    function mul(t, e) {
      var r,
          i,
          s,
          a = _typeof(t),
          n = _typeof(e);

      if (isNumerable(a, t) && isNumerable(n, e)) return t * e;

      if ($bm_isInstanceOfArray(t) && isNumerable(n, e)) {
        for (s = t.length, r = createTypedArray("float32", s), i = 0; i < s; i += 1) {
          r[i] = t[i] * e;
        }

        return r;
      }

      if (isNumerable(a, t) && $bm_isInstanceOfArray(e)) {
        for (s = e.length, r = createTypedArray("float32", s), i = 0; i < s; i += 1) {
          r[i] = t * e[i];
        }

        return r;
      }

      return 0;
    }

    function div(t, e) {
      var r,
          i,
          s,
          a = _typeof(t),
          n = _typeof(e);

      if (isNumerable(a, t) && isNumerable(n, e)) return t / e;

      if ($bm_isInstanceOfArray(t) && isNumerable(n, e)) {
        for (s = t.length, r = createTypedArray("float32", s), i = 0; i < s; i += 1) {
          r[i] = t[i] / e;
        }

        return r;
      }

      if (isNumerable(a, t) && $bm_isInstanceOfArray(e)) {
        for (s = e.length, r = createTypedArray("float32", s), i = 0; i < s; i += 1) {
          r[i] = t / e[i];
        }

        return r;
      }

      return 0;
    }

    function mod(t, e) {
      return "string" == typeof t && (t = parseInt(t, 10)), "string" == typeof e && (e = parseInt(e, 10)), t % e;
    }

    var $bm_sum = sum,
        $bm_sub = sub,
        $bm_mul = mul,
        $bm_div = div,
        $bm_mod = mod;

    function clamp(t, e, r) {
      if (r < e) {
        var i = r;
        r = e, e = i;
      }

      return Math.min(Math.max(t, e), r);
    }

    function radiansToDegrees(t) {
      return t / degToRads;
    }

    var radians_to_degrees = radiansToDegrees;

    function degreesToRadians(t) {
      return t * degToRads;
    }

    var degrees_to_radians = radiansToDegrees,
        helperLengthArray = [0, 0, 0, 0, 0, 0];

    function length(t, e) {
      if ("number" == typeof t || t instanceof Number) return e = e || 0, Math.abs(t - e);
      var r;
      e || (e = helperLengthArray);
      var i = Math.min(t.length, e.length),
          s = 0;

      for (r = 0; r < i; r += 1) {
        s += Math.pow(e[r] - t[r], 2);
      }

      return Math.sqrt(s);
    }

    function normalize(t) {
      return div(t, length(t));
    }

    function rgbToHsl(t) {
      var e,
          r,
          i = t[0],
          s = t[1],
          a = t[2],
          n = Math.max(i, s, a),
          o = Math.min(i, s, a),
          h = (n + o) / 2;
      if (n === o) r = e = 0;else {
        var l = n - o;

        switch (r = .5 < h ? l / (2 - n - o) : l / (n + o), n) {
          case i:
            e = (s - a) / l + (s < a ? 6 : 0);
            break;

          case s:
            e = (a - i) / l + 2;
            break;

          case a:
            e = (i - s) / l + 4;
        }

        e /= 6;
      }
      return [e, r, h, t[3]];
    }

    function hue2rgb(t, e, r) {
      return r < 0 && (r += 1), 1 < r && (r -= 1), r < 1 / 6 ? t + 6 * (e - t) * r : r < .5 ? e : r < 2 / 3 ? t + (e - t) * (2 / 3 - r) * 6 : t;
    }

    function hslToRgb(t) {
      var e,
          r,
          i,
          s = t[0],
          a = t[1],
          n = t[2];
      if (0 === a) r = i = e = n;else {
        var o = n < .5 ? n * (1 + a) : n + a - n * a,
            h = 2 * n - o;
        e = hue2rgb(h, o, s + 1 / 3), r = hue2rgb(h, o, s), i = hue2rgb(h, o, s - 1 / 3);
      }
      return [e, r, i, t[3]];
    }

    function linear(t, e, r, i, s) {
      if (void 0 !== i && void 0 !== s || (i = e, s = r, e = 0, r = 1), r < e) {
        var a = r;
        r = e, e = a;
      }

      if (t <= e) return i;
      if (r <= t) return s;
      var n,
          o = r === e ? 0 : (t - e) / (r - e);
      if (!i.length) return i + (s - i) * o;
      var h = i.length,
          l = createTypedArray("float32", h);

      for (n = 0; n < h; n += 1) {
        l[n] = i[n] + (s[n] - i[n]) * o;
      }

      return l;
    }

    function random(t, e) {
      if (void 0 === e && (void 0 === t ? (t = 0, e = 1) : (e = t, t = void 0)), e.length) {
        var r,
            i = e.length;
        t || (t = createTypedArray("float32", i));
        var s = createTypedArray("float32", i),
            a = BMMath.random();

        for (r = 0; r < i; r += 1) {
          s[r] = t[r] + a * (e[r] - t[r]);
        }

        return s;
      }

      return void 0 === t && (t = 0), t + BMMath.random() * (e - t);
    }

    function createPath(t, e, r, i) {
      var s,
          a = t.length,
          n = shapePool.newElement();
      n.setPathData(!!i, a);
      var o,
          h,
          l = [0, 0];

      for (s = 0; s < a; s += 1) {
        o = e && e[s] ? e[s] : l, h = r && r[s] ? r[s] : l, n.setTripleAt(t[s][0], t[s][1], h[0] + t[s][0], h[1] + t[s][1], o[0] + t[s][0], o[1] + t[s][1], s, !0);
      }

      return n;
    }

    function initiateExpression(elem, data, property) {
      var val = data.x,
          needsVelocity = /velocity(?![\w\d])/.test(val),
          _needsRandom = -1 !== val.indexOf("random"),
          elemType = elem.data.ty,
          transform,
          $bm_transform,
          content,
          effect,
          thisProperty = property;

      thisProperty.valueAtTime = thisProperty.getValueAtTime, Object.defineProperty(thisProperty, "value", {
        get: function get() {
          return thisProperty.v;
        }
      }), elem.comp.frameDuration = 1 / elem.comp.globalData.frameRate, elem.comp.displayStartTime = 0;

      var inPoint = elem.data.ip / elem.comp.globalData.frameRate,
          outPoint = elem.data.op / elem.comp.globalData.frameRate,
          width = elem.data.sw ? elem.data.sw : 0,
          height = elem.data.sh ? elem.data.sh : 0,
          name = elem.data.nm,
          loopIn,
          loop_in,
          loopOut,
          loop_out,
          smooth,
          toWorld,
          fromWorld,
          fromComp,
          toComp,
          fromCompToSurface,
          position,
          rotation,
          anchorPoint,
          scale,
          thisLayer,
          thisComp,
          mask,
          valueAtTime,
          velocityAtTime,
          scoped_bm_rt,
          expression_function = eval("[function _expression_function(){" + val + ";scoped_bm_rt=$bm_rt}]")[0],
          numKeys = property.kf ? data.k.length : 0,
          active = !this.data || !0 !== this.data.hd,
          wiggle = function (t, e) {
        var r,
            i,
            s = this.pv.length ? this.pv.length : 1,
            a = createTypedArray("float32", s);
        var n = Math.floor(5 * time);

        for (i = r = 0; r < n;) {
          for (i = 0; i < s; i += 1) {
            a[i] += -e + 2 * e * BMMath.random();
          }

          r += 1;
        }

        var o = 5 * time,
            h = o - Math.floor(o),
            l = createTypedArray("float32", s);

        if (1 < s) {
          for (i = 0; i < s; i += 1) {
            l[i] = this.pv[i] + a[i] + (-e + 2 * e * BMMath.random()) * h;
          }

          return l;
        }

        return this.pv + a[0] + (-e + 2 * e * BMMath.random()) * h;
      }.bind(this);

      function loopInDuration(t, e) {
        return loopIn(t, e, !0);
      }

      function loopOutDuration(t, e) {
        return loopOut(t, e, !0);
      }

      thisProperty.loopIn && (loopIn = thisProperty.loopIn.bind(thisProperty), loop_in = loopIn), thisProperty.loopOut && (loopOut = thisProperty.loopOut.bind(thisProperty), loop_out = loopOut), thisProperty.smooth && (smooth = thisProperty.smooth.bind(thisProperty)), this.getValueAtTime && (valueAtTime = this.getValueAtTime.bind(this)), this.getVelocityAtTime && (velocityAtTime = this.getVelocityAtTime.bind(this));
      var comp = elem.comp.globalData.projectInterface.bind(elem.comp.globalData.projectInterface),
          time,
          velocity,
          value,
          text,
          textIndex,
          textTotal,
          selectorValue;

      function lookAt(t, e) {
        var r = [e[0] - t[0], e[1] - t[1], e[2] - t[2]],
            i = Math.atan2(r[0], Math.sqrt(r[1] * r[1] + r[2] * r[2])) / degToRads;
        return [-Math.atan2(r[1], r[2]) / degToRads, i, 0];
      }

      function easeOut(t, e, r, i, s) {
        return applyEase(easeOutBez, t, e, r, i, s);
      }

      function easeIn(t, e, r, i, s) {
        return applyEase(easeInBez, t, e, r, i, s);
      }

      function ease(t, e, r, i, s) {
        return applyEase(easeInOutBez, t, e, r, i, s);
      }

      function applyEase(t, e, r, i, s, a) {
        void 0 === s ? (s = r, a = i) : e = (e - r) / (i - r), 1 < e ? e = 1 : e < 0 && (e = 0);
        var n = t(e);

        if ($bm_isInstanceOfArray(s)) {
          var o,
              h = s.length,
              l = createTypedArray("float32", h);

          for (o = 0; o < h; o += 1) {
            l[o] = (a[o] - s[o]) * n + s[o];
          }

          return l;
        }

        return (a - s) * n + s;
      }

      function nearestKey(t) {
        var e,
            r,
            i,
            s = data.k.length;
        if (data.k.length && "number" != typeof data.k[0]) {
          if (r = -1, (t *= elem.comp.globalData.frameRate) < data.k[0].t) r = 1, i = data.k[0].t;else {
            for (e = 0; e < s - 1; e += 1) {
              if (t === data.k[e].t) {
                r = e + 1, i = data.k[e].t;
                break;
              }

              if (t > data.k[e].t && t < data.k[e + 1].t) {
                i = t - data.k[e].t > data.k[e + 1].t - t ? (r = e + 2, data.k[e + 1].t) : (r = e + 1, data.k[e].t);
                break;
              }
            }

            -1 === r && (r = e + 1, i = data.k[e].t);
          }
        } else i = r = 0;
        var a = {};
        return a.index = r, a.time = i / elem.comp.globalData.frameRate, a;
      }

      function key(t) {
        var e, r, i;
        if (!data.k.length || "number" == typeof data.k[0]) throw new Error("The property has no keyframe at index " + t);
        t -= 1, e = {
          time: data.k[t].t / elem.comp.globalData.frameRate,
          value: []
        };
        var s = Object.prototype.hasOwnProperty.call(data.k[t], "s") ? data.k[t].s : data.k[t - 1].e;

        for (i = s.length, r = 0; r < i; r += 1) {
          e[r] = s[r], e.value[r] = s[r];
        }

        return e;
      }

      function framesToTime(t, e) {
        return e || (e = elem.comp.globalData.frameRate), t / e;
      }

      function timeToFrames(t, e) {
        return t || 0 === t || (t = time), e || (e = elem.comp.globalData.frameRate), t * e;
      }

      function seedRandom(t) {
        BMMath.seedrandom(randSeed + t);
      }

      function sourceRectAtTime() {
        return elem.sourceRectAtTime();
      }

      function substring(t, e) {
        return "string" == typeof value ? void 0 === e ? value.substring(t) : value.substring(t, e) : "";
      }

      function substr(t, e) {
        return "string" == typeof value ? void 0 === e ? value.substr(t) : value.substr(t, e) : "";
      }

      function posterizeTime(t) {
        time = 0 === t ? 0 : Math.floor(time * t) / t, value = valueAtTime(time);
      }

      var index = elem.data.ind,
          hasParent = !(!elem.hierarchy || !elem.hierarchy.length),
          parent,
          randSeed = Math.floor(1e6 * Math.random()),
          globalData = elem.globalData;

      function executeExpression(t) {
        return value = t, _needsRandom && seedRandom(randSeed), this.frameExpressionId === elem.globalData.frameId && "textSelector" !== this.propType ? value : ("textSelector" === this.propType && (textIndex = this.textIndex, textTotal = this.textTotal, selectorValue = this.selectorValue), thisLayer || (text = elem.layerInterface.text, thisLayer = elem.layerInterface, thisComp = elem.comp.compInterface, toWorld = thisLayer.toWorld.bind(thisLayer), fromWorld = thisLayer.fromWorld.bind(thisLayer), fromComp = thisLayer.fromComp.bind(thisLayer), toComp = thisLayer.toComp.bind(thisLayer), mask = thisLayer.mask ? thisLayer.mask.bind(thisLayer) : null, fromCompToSurface = fromComp), transform || (transform = elem.layerInterface("ADBE Transform Group"), ($bm_transform = transform) && (anchorPoint = transform.anchorPoint)), 4 !== elemType || content || (content = thisLayer("ADBE Root Vectors Group")), effect || (effect = thisLayer(4)), (hasParent = !(!elem.hierarchy || !elem.hierarchy.length)) && !parent && (parent = elem.hierarchy[0].layerInterface), time = this.comp.renderedFrame / this.comp.globalData.frameRate, needsVelocity && (velocity = velocityAtTime(time)), expression_function(), this.frameExpressionId = elem.globalData.frameId, "shape" === scoped_bm_rt.propType && (scoped_bm_rt = scoped_bm_rt.v), scoped_bm_rt);
      }

      return executeExpression;
    }

    return ob.initiateExpression = initiateExpression, ob;
  }(),
      expressionHelpers = {
    searchExpressions: function searchExpressions(t, e, r) {
      e.x && (r.k = !0, r.x = !0, r.initiateExpression = ExpressionManager.initiateExpression, r.effectsSequence.push(r.initiateExpression(t, e, r).bind(r)));
    },
    getSpeedAtTime: function getSpeedAtTime(t) {
      var e = this.getValueAtTime(t),
          r = this.getValueAtTime(t + -.01),
          i = 0;

      if (e.length) {
        var s;

        for (s = 0; s < e.length; s += 1) {
          i += Math.pow(r[s] - e[s], 2);
        }

        i = 100 * Math.sqrt(i);
      } else i = 0;

      return i;
    },
    getVelocityAtTime: function getVelocityAtTime(t) {
      if (void 0 !== this.vel) return this.vel;
      var e,
          r,
          i = this.getValueAtTime(t),
          s = this.getValueAtTime(t + -.001);
      if (i.length) for (e = createTypedArray("float32", i.length), r = 0; r < i.length; r += 1) {
        e[r] = (s[r] - i[r]) / -.001;
      } else e = (s - i) / -.001;
      return e;
    },
    getValueAtTime: function getValueAtTime(t) {
      return t *= this.elem.globalData.frameRate, (t -= this.offsetTime) !== this._cachingAtTime.lastFrame && (this._cachingAtTime.lastIndex = this._cachingAtTime.lastFrame < t ? this._cachingAtTime.lastIndex : 0, this._cachingAtTime.value = this.interpolateValue(t, this._cachingAtTime), this._cachingAtTime.lastFrame = t), this._cachingAtTime.value;
    },
    getStaticValueAtTime: function getStaticValueAtTime() {
      return this.pv;
    },
    setGroupProperty: function setGroupProperty(t) {
      this.propertyGroup = t;
    }
  };

  !function () {
    function o(t, e, r) {
      if (!this.k || !this.keyframes) return this.pv;
      t = t ? t.toLowerCase() : "";
      var i,
          s,
          a,
          n,
          o,
          h = this.comp.renderedFrame,
          l = this.keyframes,
          p = l[l.length - 1].t;
      if (h <= p) return this.pv;

      if (r ? s = p - (i = e ? Math.abs(p - this.elem.comp.globalData.frameRate * e) : Math.max(0, p - this.elem.data.ip)) : ((!e || e > l.length - 1) && (e = l.length - 1), i = p - (s = l[l.length - 1 - e].t)), "pingpong" === t) {
        if (Math.floor((h - s) / i) % 2 != 0) return this.getValueAtTime((i - (h - s) % i + s) / this.comp.globalData.frameRate, 0);
      } else {
        if ("offset" === t) {
          var m = this.getValueAtTime(s / this.comp.globalData.frameRate, 0),
              f = this.getValueAtTime(p / this.comp.globalData.frameRate, 0),
              c = this.getValueAtTime(((h - s) % i + s) / this.comp.globalData.frameRate, 0),
              d = Math.floor((h - s) / i);

          if (this.pv.length) {
            for (n = (o = new Array(m.length)).length, a = 0; a < n; a += 1) {
              o[a] = (f[a] - m[a]) * d + c[a];
            }

            return o;
          }

          return (f - m) * d + c;
        }

        if ("continue" === t) {
          var u = this.getValueAtTime(p / this.comp.globalData.frameRate, 0),
              y = this.getValueAtTime((p - .001) / this.comp.globalData.frameRate, 0);

          if (this.pv.length) {
            for (n = (o = new Array(u.length)).length, a = 0; a < n; a += 1) {
              o[a] = u[a] + (u[a] - y[a]) * ((h - p) / this.comp.globalData.frameRate) / 5e-4;
            }

            return o;
          }

          return u + (h - p) / .001 * (u - y);
        }
      }

      return this.getValueAtTime(((h - s) % i + s) / this.comp.globalData.frameRate, 0);
    }

    function h(t, e, r) {
      if (!this.k) return this.pv;
      t = t ? t.toLowerCase() : "";
      var i,
          s,
          a,
          n,
          o,
          h = this.comp.renderedFrame,
          l = this.keyframes,
          p = l[0].t;
      if (p <= h) return this.pv;

      if (r ? s = p + (i = e ? Math.abs(this.elem.comp.globalData.frameRate * e) : Math.max(0, this.elem.data.op - p)) : ((!e || e > l.length - 1) && (e = l.length - 1), i = (s = l[e].t) - p), "pingpong" === t) {
        if (Math.floor((p - h) / i) % 2 == 0) return this.getValueAtTime(((p - h) % i + p) / this.comp.globalData.frameRate, 0);
      } else {
        if ("offset" === t) {
          var m = this.getValueAtTime(p / this.comp.globalData.frameRate, 0),
              f = this.getValueAtTime(s / this.comp.globalData.frameRate, 0),
              c = this.getValueAtTime((i - (p - h) % i + p) / this.comp.globalData.frameRate, 0),
              d = Math.floor((p - h) / i) + 1;

          if (this.pv.length) {
            for (n = (o = new Array(m.length)).length, a = 0; a < n; a += 1) {
              o[a] = c[a] - (f[a] - m[a]) * d;
            }

            return o;
          }

          return c - (f - m) * d;
        }

        if ("continue" === t) {
          var u = this.getValueAtTime(p / this.comp.globalData.frameRate, 0),
              y = this.getValueAtTime((p + .001) / this.comp.globalData.frameRate, 0);

          if (this.pv.length) {
            for (n = (o = new Array(u.length)).length, a = 0; a < n; a += 1) {
              o[a] = u[a] + (u[a] - y[a]) * (p - h) / .001;
            }

            return o;
          }

          return u + (u - y) * (p - h) / .001;
        }
      }

      return this.getValueAtTime((i - ((p - h) % i + p)) / this.comp.globalData.frameRate, 0);
    }

    function l(t, e) {
      if (!this.k) return this.pv;
      if (t = .5 * (t || .4), (e = Math.floor(e || 5)) <= 1) return this.pv;
      var r,
          i,
          s = this.comp.renderedFrame / this.comp.globalData.frameRate,
          a = s - t,
          n = 1 < e ? (s + t - a) / (e - 1) : 1,
          o = 0,
          h = 0;

      for (r = this.pv.length ? createTypedArray("float32", this.pv.length) : 0; o < e;) {
        if (i = this.getValueAtTime(a + o * n), this.pv.length) for (h = 0; h < this.pv.length; h += 1) {
          r[h] += i[h];
        } else r += i;
        o += 1;
      }

      if (this.pv.length) for (h = 0; h < this.pv.length; h += 1) {
        r[h] /= e;
      } else r /= e;
      return r;
    }

    var s = TransformPropertyFactory.getTransformProperty;

    TransformPropertyFactory.getTransformProperty = function (t, e, r) {
      var i = s(t, e, r);
      return i.dynamicProperties.length ? i.getValueAtTime = function (t) {
        this._transformCachingAtTime || (this._transformCachingAtTime = {
          v: new Matrix()
        });
        var e = this._transformCachingAtTime.v;

        if (e.cloneFromProps(this.pre.props), this.appliedTransformations < 1) {
          var r = this.a.getValueAtTime(t);
          e.translate(-r[0] * this.a.mult, -r[1] * this.a.mult, r[2] * this.a.mult);
        }

        if (this.appliedTransformations < 2) {
          var i = this.s.getValueAtTime(t);
          e.scale(i[0] * this.s.mult, i[1] * this.s.mult, i[2] * this.s.mult);
        }

        if (this.sk && this.appliedTransformations < 3) {
          var s = this.sk.getValueAtTime(t),
              a = this.sa.getValueAtTime(t);
          e.skewFromAxis(-s * this.sk.mult, a * this.sa.mult);
        }

        if (this.r && this.appliedTransformations < 4) {
          var n = this.r.getValueAtTime(t);
          e.rotate(-n * this.r.mult);
        } else if (!this.r && this.appliedTransformations < 4) {
          var o = this.rz.getValueAtTime(t),
              h = this.ry.getValueAtTime(t),
              l = this.rx.getValueAtTime(t),
              p = this.or.getValueAtTime(t);
          e.rotateZ(-o * this.rz.mult).rotateY(h * this.ry.mult).rotateX(l * this.rx.mult).rotateZ(-p[2] * this.or.mult).rotateY(p[1] * this.or.mult).rotateX(p[0] * this.or.mult);
        }

        if (this.data.p && this.data.p.s) {
          var m = this.px.getValueAtTime(t),
              f = this.py.getValueAtTime(t);

          if (this.data.p.z) {
            var c = this.pz.getValueAtTime(t);
            e.translate(m * this.px.mult, f * this.py.mult, -c * this.pz.mult);
          } else e.translate(m * this.px.mult, f * this.py.mult, 0);
        } else {
          var d = this.p.getValueAtTime(t);
          e.translate(d[0] * this.p.mult, d[1] * this.p.mult, -d[2] * this.p.mult);
        }

        return e;
      }.bind(i) : i.getValueAtTime = function () {
        return this.v.clone(new Matrix());
      }.bind(i), i.setGroupProperty = expressionHelpers.setGroupProperty, i;
    };

    var p = PropertyFactory.getProp;

    PropertyFactory.getProp = function (t, e, r, i, s) {
      var a = p(t, e, r, i, s);
      a.kf ? a.getValueAtTime = expressionHelpers.getValueAtTime.bind(a) : a.getValueAtTime = expressionHelpers.getStaticValueAtTime.bind(a), a.setGroupProperty = expressionHelpers.setGroupProperty, a.loopOut = o, a.loopIn = h, a.smooth = l, a.getVelocityAtTime = expressionHelpers.getVelocityAtTime.bind(a), a.getSpeedAtTime = expressionHelpers.getSpeedAtTime.bind(a), a.numKeys = 1 === e.a ? e.k.length : 0, a.propertyIndex = e.ix;
      var n = 0;
      return 0 !== r && (n = createTypedArray("float32", 1 === e.a ? e.k[0].s.length : e.k.length)), a._cachingAtTime = {
        lastFrame: initialDefaultFrame,
        lastIndex: 0,
        value: n
      }, expressionHelpers.searchExpressions(t, e, a), a.k && s.addDynamicProperty(a), a;
    };

    var t = ShapePropertyFactory.getConstructorFunction(),
        e = ShapePropertyFactory.getKeyframedConstructorFunction();

    function r() {}

    r.prototype = {
      vertices: function vertices(t, e) {
        this.k && this.getValue();
        var r,
            i = this.v;
        void 0 !== e && (i = this.getValueAtTime(e, 0));
        var s = i._length,
            a = i[t],
            n = i.v,
            o = createSizedArray(s);

        for (r = 0; r < s; r += 1) {
          o[r] = "i" === t || "o" === t ? [a[r][0] - n[r][0], a[r][1] - n[r][1]] : [a[r][0], a[r][1]];
        }

        return o;
      },
      points: function points(t) {
        return this.vertices("v", t);
      },
      inTangents: function inTangents(t) {
        return this.vertices("i", t);
      },
      outTangents: function outTangents(t) {
        return this.vertices("o", t);
      },
      isClosed: function isClosed() {
        return this.v.c;
      },
      pointOnPath: function pointOnPath(t, e) {
        var r = this.v;
        void 0 !== e && (r = this.getValueAtTime(e, 0)), this._segmentsLength || (this._segmentsLength = bez.getSegmentsLength(r));

        for (var i, s = this._segmentsLength, a = s.lengths, n = s.totalLength * t, o = 0, h = a.length, l = 0; o < h;) {
          if (l + a[o].addedLength > n) {
            var p = o,
                m = r.c && o === h - 1 ? 0 : o + 1,
                f = (n - l) / a[o].addedLength;
            i = bez.getPointInSegment(r.v[p], r.v[m], r.o[p], r.i[m], f, a[o]);
            break;
          }

          l += a[o].addedLength, o += 1;
        }

        return i || (i = r.c ? [r.v[0][0], r.v[0][1]] : [r.v[r._length - 1][0], r.v[r._length - 1][1]]), i;
      },
      vectorOnPath: function vectorOnPath(t, e, r) {
        1 == t ? t = this.v.c : 0 == t && (t = .999);
        var i = this.pointOnPath(t, e),
            s = this.pointOnPath(t + .001, e),
            a = s[0] - i[0],
            n = s[1] - i[1],
            o = Math.sqrt(Math.pow(a, 2) + Math.pow(n, 2));
        return 0 === o ? [0, 0] : "tangent" === r ? [a / o, n / o] : [-n / o, a / o];
      },
      tangentOnPath: function tangentOnPath(t, e) {
        return this.vectorOnPath(t, e, "tangent");
      },
      normalOnPath: function normalOnPath(t, e) {
        return this.vectorOnPath(t, e, "normal");
      },
      setGroupProperty: expressionHelpers.setGroupProperty,
      getValueAtTime: expressionHelpers.getStaticValueAtTime
    }, extendPrototype([r], t), extendPrototype([r], e), e.prototype.getValueAtTime = function (t) {
      return this._cachingAtTime || (this._cachingAtTime = {
        shapeValue: shapePool.clone(this.pv),
        lastIndex: 0,
        lastTime: initialDefaultFrame
      }), t *= this.elem.globalData.frameRate, (t -= this.offsetTime) !== this._cachingAtTime.lastTime && (this._cachingAtTime.lastIndex = this._cachingAtTime.lastTime < t ? this._caching.lastIndex : 0, this._cachingAtTime.lastTime = t, this.interpolateShape(t, this._cachingAtTime.shapeValue, this._cachingAtTime)), this._cachingAtTime.shapeValue;
    }, e.prototype.initiateExpression = ExpressionManager.initiateExpression;
    var n = ShapePropertyFactory.getShapeProp;

    ShapePropertyFactory.getShapeProp = function (t, e, r, i, s) {
      var a = n(t, e, r, i, s);
      return a.propertyIndex = e.ix, a.lock = !1, 3 === r ? expressionHelpers.searchExpressions(t, e.pt, a) : 4 === r && expressionHelpers.searchExpressions(t, e.ks, a), a.k && t.addDynamicProperty(a), a;
    };
  }(), TextProperty.prototype.getExpressionValue = function (t, e) {
    var r = this.calculateExpression(e);
    if (t.t === r) return t;
    var i = {};
    return this.copyData(i, t), i.t = r.toString(), i.__complete = !1, i;
  }, TextProperty.prototype.searchProperty = function () {
    var t = this.searchKeyframes(),
        e = this.searchExpressions();
    return this.kf = t || e, this.kf;
  }, TextProperty.prototype.searchExpressions = function () {
    return this.data.d.x ? (this.calculateExpression = ExpressionManager.initiateExpression.bind(this)(this.elem, this.data.d, this), this.addEffect(this.getExpressionValue.bind(this)), !0) : null;
  };

  var ShapePathInterface = function ShapePathInterface(t, e, r) {
    var i = e.sh;

    function s(t) {
      return "Shape" === t || "shape" === t || "Path" === t || "path" === t || "ADBE Vector Shape" === t || 2 === t ? s.path : null;
    }

    var a = propertyGroupFactory(s, r);
    return i.setGroupProperty(PropertyInterface("Path", a)), Object.defineProperties(s, {
      path: {
        get: function get() {
          return i.k && i.getValue(), i;
        }
      },
      shape: {
        get: function get() {
          return i.k && i.getValue(), i;
        }
      },
      _name: {
        value: t.nm
      },
      ix: {
        value: t.ix
      },
      propertyIndex: {
        value: t.ix
      },
      mn: {
        value: t.mn
      },
      propertyGroup: {
        value: r
      }
    }), s;
  },
      propertyGroupFactory = function propertyGroupFactory(e, r) {
    return function (t) {
      return (t = void 0 === t ? 1 : t) <= 0 ? e : r(t - 1);
    };
  },
      PropertyInterface = function PropertyInterface(t, e) {
    var r = {
      _name: t
    };
    return function (t) {
      return (t = void 0 === t ? 1 : t) <= 0 ? r : e(t - 1);
    };
  },
      ShapeExpressionInterface = function () {
    function n(t, e, r) {
      var i,
          s = [],
          a = t ? t.length : 0;

      for (i = 0; i < a; i += 1) {
        "gr" === t[i].ty ? s.push(o(t[i], e[i], r)) : "fl" === t[i].ty ? s.push(h(t[i], e[i], r)) : "st" === t[i].ty ? s.push(m(t[i], e[i], r)) : "tm" === t[i].ty ? s.push(f(t[i], e[i], r)) : "tr" === t[i].ty || ("el" === t[i].ty ? s.push(d(t[i], e[i], r)) : "sr" === t[i].ty ? s.push(u(t[i], e[i], r)) : "sh" === t[i].ty ? s.push(ShapePathInterface(t[i], e[i], r)) : "rc" === t[i].ty ? s.push(y(t[i], e[i], r)) : "rd" === t[i].ty ? s.push(g(t[i], e[i], r)) : "rp" === t[i].ty ? s.push(v(t[i], e[i], r)) : "gf" === t[i].ty ? s.push(l(t[i], e[i], r)) : s.push(p(t[i], e[i])));
      }

      return s;
    }

    function o(t, e, r) {
      var i = function i(t) {
        switch (t) {
          case "ADBE Vectors Group":
          case "Contents":
          case 2:
            return i.content;

          default:
            return i.transform;
        }
      };

      i.propertyGroup = propertyGroupFactory(i, r);

      var s = function (t, e, r) {
        var i,
            s = function s(t) {
          for (var e = 0, r = i.length; e < r;) {
            if (i[e]._name === t || i[e].mn === t || i[e].propertyIndex === t || i[e].ix === t || i[e].ind === t) return i[e];
            e += 1;
          }

          return "number" == typeof t ? i[t - 1] : null;
        };

        s.propertyGroup = propertyGroupFactory(s, r), i = n(t.it, e.it, s.propertyGroup), s.numProperties = i.length;
        var a = c(t.it[t.it.length - 1], e.it[e.it.length - 1], s.propertyGroup);
        return s.transform = a, s.propertyIndex = t.cix, s._name = t.nm, s;
      }(t, e, i.propertyGroup),
          a = c(t.it[t.it.length - 1], e.it[e.it.length - 1], i.propertyGroup);

      return i.content = s, i.transform = a, Object.defineProperty(i, "_name", {
        get: function get() {
          return t.nm;
        }
      }), i.numProperties = t.np, i.propertyIndex = t.ix, i.nm = t.nm, i.mn = t.mn, i;
    }

    function h(t, e, r) {
      function i(t) {
        return "Color" === t || "color" === t ? i.color : "Opacity" === t || "opacity" === t ? i.opacity : null;
      }

      return Object.defineProperties(i, {
        color: {
          get: ExpressionPropertyInterface(e.c)
        },
        opacity: {
          get: ExpressionPropertyInterface(e.o)
        },
        _name: {
          value: t.nm
        },
        mn: {
          value: t.mn
        }
      }), e.c.setGroupProperty(PropertyInterface("Color", r)), e.o.setGroupProperty(PropertyInterface("Opacity", r)), i;
    }

    function l(t, e, r) {
      function i(t) {
        return "Start Point" === t || "start point" === t ? i.startPoint : "End Point" === t || "end point" === t ? i.endPoint : "Opacity" === t || "opacity" === t ? i.opacity : null;
      }

      return Object.defineProperties(i, {
        startPoint: {
          get: ExpressionPropertyInterface(e.s)
        },
        endPoint: {
          get: ExpressionPropertyInterface(e.e)
        },
        opacity: {
          get: ExpressionPropertyInterface(e.o)
        },
        type: {
          get: function get() {
            return "a";
          }
        },
        _name: {
          value: t.nm
        },
        mn: {
          value: t.mn
        }
      }), e.s.setGroupProperty(PropertyInterface("Start Point", r)), e.e.setGroupProperty(PropertyInterface("End Point", r)), e.o.setGroupProperty(PropertyInterface("Opacity", r)), i;
    }

    function p() {
      return function () {
        return null;
      };
    }

    function m(t, e, r) {
      var i,
          s = propertyGroupFactory(l, r),
          a = propertyGroupFactory(h, s);
      var n,
          o = t.d ? t.d.length : 0,
          h = {};

      for (i = 0; i < o; i += 1) {
        n = i, Object.defineProperty(h, t.d[n].nm, {
          get: ExpressionPropertyInterface(e.d.dataProps[n].p)
        }), e.d.dataProps[i].p.setGroupProperty(a);
      }

      function l(t) {
        return "Color" === t || "color" === t ? l.color : "Opacity" === t || "opacity" === t ? l.opacity : "Stroke Width" === t || "stroke width" === t ? l.strokeWidth : null;
      }

      return Object.defineProperties(l, {
        color: {
          get: ExpressionPropertyInterface(e.c)
        },
        opacity: {
          get: ExpressionPropertyInterface(e.o)
        },
        strokeWidth: {
          get: ExpressionPropertyInterface(e.w)
        },
        dash: {
          get: function get() {
            return h;
          }
        },
        _name: {
          value: t.nm
        },
        mn: {
          value: t.mn
        }
      }), e.c.setGroupProperty(PropertyInterface("Color", s)), e.o.setGroupProperty(PropertyInterface("Opacity", s)), e.w.setGroupProperty(PropertyInterface("Stroke Width", s)), l;
    }

    function f(e, t, r) {
      function i(t) {
        return t === e.e.ix || "End" === t || "end" === t ? i.end : t === e.s.ix ? i.start : t === e.o.ix ? i.offset : null;
      }

      var s = propertyGroupFactory(i, r);
      return i.propertyIndex = e.ix, t.s.setGroupProperty(PropertyInterface("Start", s)), t.e.setGroupProperty(PropertyInterface("End", s)), t.o.setGroupProperty(PropertyInterface("Offset", s)), i.propertyIndex = e.ix, i.propertyGroup = r, Object.defineProperties(i, {
        start: {
          get: ExpressionPropertyInterface(t.s)
        },
        end: {
          get: ExpressionPropertyInterface(t.e)
        },
        offset: {
          get: ExpressionPropertyInterface(t.o)
        },
        _name: {
          value: e.nm
        }
      }), i.mn = e.mn, i;
    }

    function c(e, t, r) {
      function i(t) {
        return e.a.ix === t || "Anchor Point" === t ? i.anchorPoint : e.o.ix === t || "Opacity" === t ? i.opacity : e.p.ix === t || "Position" === t ? i.position : e.r.ix === t || "Rotation" === t || "ADBE Vector Rotation" === t ? i.rotation : e.s.ix === t || "Scale" === t ? i.scale : e.sk && e.sk.ix === t || "Skew" === t ? i.skew : e.sa && e.sa.ix === t || "Skew Axis" === t ? i.skewAxis : null;
      }

      var s = propertyGroupFactory(i, r);
      return t.transform.mProps.o.setGroupProperty(PropertyInterface("Opacity", s)), t.transform.mProps.p.setGroupProperty(PropertyInterface("Position", s)), t.transform.mProps.a.setGroupProperty(PropertyInterface("Anchor Point", s)), t.transform.mProps.s.setGroupProperty(PropertyInterface("Scale", s)), t.transform.mProps.r.setGroupProperty(PropertyInterface("Rotation", s)), t.transform.mProps.sk && (t.transform.mProps.sk.setGroupProperty(PropertyInterface("Skew", s)), t.transform.mProps.sa.setGroupProperty(PropertyInterface("Skew Angle", s))), t.transform.op.setGroupProperty(PropertyInterface("Opacity", s)), Object.defineProperties(i, {
        opacity: {
          get: ExpressionPropertyInterface(t.transform.mProps.o)
        },
        position: {
          get: ExpressionPropertyInterface(t.transform.mProps.p)
        },
        anchorPoint: {
          get: ExpressionPropertyInterface(t.transform.mProps.a)
        },
        scale: {
          get: ExpressionPropertyInterface(t.transform.mProps.s)
        },
        rotation: {
          get: ExpressionPropertyInterface(t.transform.mProps.r)
        },
        skew: {
          get: ExpressionPropertyInterface(t.transform.mProps.sk)
        },
        skewAxis: {
          get: ExpressionPropertyInterface(t.transform.mProps.sa)
        },
        _name: {
          value: e.nm
        }
      }), i.ty = "tr", i.mn = e.mn, i.propertyGroup = r, i;
    }

    function d(e, t, r) {
      function i(t) {
        return e.p.ix === t ? i.position : e.s.ix === t ? i.size : null;
      }

      var s = propertyGroupFactory(i, r);
      i.propertyIndex = e.ix;
      var a = "tm" === t.sh.ty ? t.sh.prop : t.sh;
      return a.s.setGroupProperty(PropertyInterface("Size", s)), a.p.setGroupProperty(PropertyInterface("Position", s)), Object.defineProperties(i, {
        size: {
          get: ExpressionPropertyInterface(a.s)
        },
        position: {
          get: ExpressionPropertyInterface(a.p)
        },
        _name: {
          value: e.nm
        }
      }), i.mn = e.mn, i;
    }

    function u(e, t, r) {
      function i(t) {
        return e.p.ix === t ? i.position : e.r.ix === t ? i.rotation : e.pt.ix === t ? i.points : e.or.ix === t || "ADBE Vector Star Outer Radius" === t ? i.outerRadius : e.os.ix === t ? i.outerRoundness : !e.ir || e.ir.ix !== t && "ADBE Vector Star Inner Radius" !== t ? e.is && e.is.ix === t ? i.innerRoundness : null : i.innerRadius;
      }

      var s = propertyGroupFactory(i, r),
          a = "tm" === t.sh.ty ? t.sh.prop : t.sh;
      return i.propertyIndex = e.ix, a.or.setGroupProperty(PropertyInterface("Outer Radius", s)), a.os.setGroupProperty(PropertyInterface("Outer Roundness", s)), a.pt.setGroupProperty(PropertyInterface("Points", s)), a.p.setGroupProperty(PropertyInterface("Position", s)), a.r.setGroupProperty(PropertyInterface("Rotation", s)), e.ir && (a.ir.setGroupProperty(PropertyInterface("Inner Radius", s)), a.is.setGroupProperty(PropertyInterface("Inner Roundness", s))), Object.defineProperties(i, {
        position: {
          get: ExpressionPropertyInterface(a.p)
        },
        rotation: {
          get: ExpressionPropertyInterface(a.r)
        },
        points: {
          get: ExpressionPropertyInterface(a.pt)
        },
        outerRadius: {
          get: ExpressionPropertyInterface(a.or)
        },
        outerRoundness: {
          get: ExpressionPropertyInterface(a.os)
        },
        innerRadius: {
          get: ExpressionPropertyInterface(a.ir)
        },
        innerRoundness: {
          get: ExpressionPropertyInterface(a.is)
        },
        _name: {
          value: e.nm
        }
      }), i.mn = e.mn, i;
    }

    function y(e, t, r) {
      function i(t) {
        return e.p.ix === t ? i.position : e.r.ix === t ? i.roundness : e.s.ix === t || "Size" === t || "ADBE Vector Rect Size" === t ? i.size : null;
      }

      var s = propertyGroupFactory(i, r),
          a = "tm" === t.sh.ty ? t.sh.prop : t.sh;
      return i.propertyIndex = e.ix, a.p.setGroupProperty(PropertyInterface("Position", s)), a.s.setGroupProperty(PropertyInterface("Size", s)), a.r.setGroupProperty(PropertyInterface("Rotation", s)), Object.defineProperties(i, {
        position: {
          get: ExpressionPropertyInterface(a.p)
        },
        roundness: {
          get: ExpressionPropertyInterface(a.r)
        },
        size: {
          get: ExpressionPropertyInterface(a.s)
        },
        _name: {
          value: e.nm
        }
      }), i.mn = e.mn, i;
    }

    function g(e, t, r) {
      function i(t) {
        return e.r.ix === t || "Round Corners 1" === t ? i.radius : null;
      }

      var s = propertyGroupFactory(i, r),
          a = t;
      return i.propertyIndex = e.ix, a.rd.setGroupProperty(PropertyInterface("Radius", s)), Object.defineProperties(i, {
        radius: {
          get: ExpressionPropertyInterface(a.rd)
        },
        _name: {
          value: e.nm
        }
      }), i.mn = e.mn, i;
    }

    function v(e, t, r) {
      function i(t) {
        return e.c.ix === t || "Copies" === t ? i.copies : e.o.ix === t || "Offset" === t ? i.offset : null;
      }

      var s = propertyGroupFactory(i, r),
          a = t;
      return i.propertyIndex = e.ix, a.c.setGroupProperty(PropertyInterface("Copies", s)), a.o.setGroupProperty(PropertyInterface("Offset", s)), Object.defineProperties(i, {
        copies: {
          get: ExpressionPropertyInterface(a.c)
        },
        offset: {
          get: ExpressionPropertyInterface(a.o)
        },
        _name: {
          value: e.nm
        }
      }), i.mn = e.mn, i;
    }

    return function (t, e, i) {
      var s;

      function r(t) {
        if ("number" == typeof t) return 0 === (t = void 0 === t ? 1 : t) ? i : s[t - 1];

        for (var e = 0, r = s.length; e < r;) {
          if (s[e]._name === t) return s[e];
          e += 1;
        }

        return null;
      }

      return r.propertyGroup = propertyGroupFactory(r, function () {
        return i;
      }), s = n(t, e, r.propertyGroup), r.numProperties = s.length, r._name = "Contents", r;
    };
  }(),
      TextExpressionInterface = function TextExpressionInterface(e) {
    var r;

    function i(t) {
      switch (t) {
        case "ADBE Text Document":
          return i.sourceText;

        default:
          return null;
      }
    }

    return Object.defineProperty(i, "sourceText", {
      get: function get() {
        e.textProperty.getValue();
        var t = e.textProperty.currentData.t;
        return void 0 !== t && (e.textProperty.currentData.t = void 0, (r = new String(t)).value = t || new String(t)), r;
      }
    }), i;
  },
      LayerExpressionInterface = function () {
    function s(t) {
      var e = new Matrix();
      void 0 !== t ? this._elem.finalTransform.mProp.getValueAtTime(t).clone(e) : this._elem.finalTransform.mProp.applyToMatrix(e);
      return e;
    }

    function a(t, e) {
      var r = this.getMatrix(e);
      return r.props[12] = 0, r.props[13] = 0, r.props[14] = 0, this.applyPoint(r, t);
    }

    function n(t, e) {
      var r = this.getMatrix(e);
      return this.applyPoint(r, t);
    }

    function o(t, e) {
      var r = this.getMatrix(e);
      return r.props[12] = 0, r.props[13] = 0, r.props[14] = 0, this.invertPoint(r, t);
    }

    function h(t, e) {
      var r = this.getMatrix(e);
      return this.invertPoint(r, t);
    }

    function l(t, e) {
      if (this._elem.hierarchy && this._elem.hierarchy.length) {
        var r,
            i = this._elem.hierarchy.length;

        for (r = 0; r < i; r += 1) {
          this._elem.hierarchy[r].finalTransform.mProp.applyToMatrix(t);
        }
      }

      return t.applyToPointArray(e[0], e[1], e[2] || 0);
    }

    function p(t, e) {
      if (this._elem.hierarchy && this._elem.hierarchy.length) {
        var r,
            i = this._elem.hierarchy.length;

        for (r = 0; r < i; r += 1) {
          this._elem.hierarchy[r].finalTransform.mProp.applyToMatrix(t);
        }
      }

      return t.inversePoint(e);
    }

    function m(t) {
      var e = new Matrix();

      if (e.reset(), this._elem.finalTransform.mProp.applyToMatrix(e), this._elem.hierarchy && this._elem.hierarchy.length) {
        var r,
            i = this._elem.hierarchy.length;

        for (r = 0; r < i; r += 1) {
          this._elem.hierarchy[r].finalTransform.mProp.applyToMatrix(e);
        }

        return e.inversePoint(t);
      }

      return e.inversePoint(t);
    }

    function f() {
      return [1, 1, 1, 1];
    }

    return function (e) {
      var r;

      function i(t) {
        switch (t) {
          case "ADBE Root Vectors Group":
          case "Contents":
          case 2:
            return i.shapeInterface;

          case 1:
          case 6:
          case "Transform":
          case "transform":
          case "ADBE Transform Group":
            return r;

          case 4:
          case "ADBE Effect Parade":
          case "effects":
          case "Effects":
            return i.effect;

          case "ADBE Text Properties":
            return i.textInterface;

          default:
            return null;
        }
      }

      i.getMatrix = s, i.invertPoint = p, i.applyPoint = l, i.toWorld = n, i.toWorldVec = a, i.fromWorld = h, i.fromWorldVec = o, i.toComp = n, i.fromComp = m, i.sampleImage = f, i.sourceRectAtTime = e.sourceRectAtTime.bind(e);
      var t = getDescriptor(r = TransformExpressionInterface((i._elem = e).finalTransform.mProp), "anchorPoint");
      return Object.defineProperties(i, {
        hasParent: {
          get: function get() {
            return e.hierarchy.length;
          }
        },
        parent: {
          get: function get() {
            return e.hierarchy[0].layerInterface;
          }
        },
        rotation: getDescriptor(r, "rotation"),
        scale: getDescriptor(r, "scale"),
        position: getDescriptor(r, "position"),
        opacity: getDescriptor(r, "opacity"),
        anchorPoint: t,
        anchor_point: t,
        transform: {
          get: function get() {
            return r;
          }
        },
        active: {
          get: function get() {
            return e.isInRange;
          }
        }
      }), i.startTime = e.data.st, i.index = e.data.ind, i.source = e.data.refId, i.height = 0 === e.data.ty ? e.data.h : 100, i.width = 0 === e.data.ty ? e.data.w : 100, i.inPoint = e.data.ip / e.comp.globalData.frameRate, i.outPoint = e.data.op / e.comp.globalData.frameRate, i._name = e.data.nm, i.registerMaskInterface = function (t) {
        i.mask = new MaskManagerInterface(t, e);
      }, i.registerEffectsInterface = function (t) {
        i.effect = t;
      }, i;
    };
  }(),
      FootageInterface = (q6 = function q6(t) {
    function e(t) {
      return "Outline" === t ? e.outlineInterface() : null;
    }

    return e._name = "Outline", e.outlineInterface = function (t) {
      var i = "",
          s = t.getFootageData();

      function a(t) {
        if (s[t]) return "object" == _typeof(s = s[i = t]) ? a : s;
        var e = t.indexOf(i);
        if (-1 === e) return "";
        var r = parseInt(t.substr(e + i.length), 10);
        return "object" == _typeof(s = s[r]) ? a : s;
      }

      return function () {
        return i = "", s = t.getFootageData(), a;
      };
    }(t), e;
  }, function (t) {
    function e(t) {
      return "Data" === t ? e.dataInterface : null;
    }

    return e._name = "Data", e.dataInterface = q6(t), e;
  }),
      q6,
      CompExpressionInterface = function CompExpressionInterface(i) {
    function t(t) {
      for (var e = 0, r = i.layers.length; e < r;) {
        if (i.layers[e].nm === t || i.layers[e].ind === t) return i.elements[e].layerInterface;
        e += 1;
      }

      return null;
    }

    return Object.defineProperty(t, "_name", {
      value: i.data.nm
    }), (t.layer = t).pixelAspect = 1, t.height = i.data.h || i.globalData.compSize.h, t.width = i.data.w || i.globalData.compSize.w, t.pixelAspect = 1, t.frameDuration = 1 / i.globalData.frameRate, t.displayStartTime = 0, t.numLayers = i.layers.length, t;
  },
      TransformExpressionInterface = function TransformExpressionInterface(t) {
    function e(t) {
      switch (t) {
        case "scale":
        case "Scale":
        case "ADBE Scale":
        case 6:
          return e.scale;

        case "rotation":
        case "Rotation":
        case "ADBE Rotation":
        case "ADBE Rotate Z":
        case 10:
          return e.rotation;

        case "ADBE Rotate X":
          return e.xRotation;

        case "ADBE Rotate Y":
          return e.yRotation;

        case "position":
        case "Position":
        case "ADBE Position":
        case 2:
          return e.position;

        case "ADBE Position_0":
          return e.xPosition;

        case "ADBE Position_1":
          return e.yPosition;

        case "ADBE Position_2":
          return e.zPosition;

        case "anchorPoint":
        case "AnchorPoint":
        case "Anchor Point":
        case "ADBE AnchorPoint":
        case 1:
          return e.anchorPoint;

        case "opacity":
        case "Opacity":
        case 11:
          return e.opacity;

        default:
          return null;
      }
    }

    var r, i, s, a;
    return Object.defineProperty(e, "rotation", {
      get: ExpressionPropertyInterface(t.r || t.rz)
    }), Object.defineProperty(e, "zRotation", {
      get: ExpressionPropertyInterface(t.rz || t.r)
    }), Object.defineProperty(e, "xRotation", {
      get: ExpressionPropertyInterface(t.rx)
    }), Object.defineProperty(e, "yRotation", {
      get: ExpressionPropertyInterface(t.ry)
    }), Object.defineProperty(e, "scale", {
      get: ExpressionPropertyInterface(t.s)
    }), t.p ? a = ExpressionPropertyInterface(t.p) : (r = ExpressionPropertyInterface(t.px), i = ExpressionPropertyInterface(t.py), t.pz && (s = ExpressionPropertyInterface(t.pz))), Object.defineProperty(e, "position", {
      get: function get() {
        return t.p ? a() : [r(), i(), s ? s() : 0];
      }
    }), Object.defineProperty(e, "xPosition", {
      get: ExpressionPropertyInterface(t.px)
    }), Object.defineProperty(e, "yPosition", {
      get: ExpressionPropertyInterface(t.py)
    }), Object.defineProperty(e, "zPosition", {
      get: ExpressionPropertyInterface(t.pz)
    }), Object.defineProperty(e, "anchorPoint", {
      get: ExpressionPropertyInterface(t.a)
    }), Object.defineProperty(e, "opacity", {
      get: ExpressionPropertyInterface(t.o)
    }), Object.defineProperty(e, "skew", {
      get: ExpressionPropertyInterface(t.sk)
    }), Object.defineProperty(e, "skewAxis", {
      get: ExpressionPropertyInterface(t.sa)
    }), Object.defineProperty(e, "orientation", {
      get: ExpressionPropertyInterface(t.or)
    }), e;
  },
      ProjectInterface = function () {
    function e(t) {
      this.compositions.push(t);
    }

    return function () {
      function t(t) {
        for (var e = 0, r = this.compositions.length; e < r;) {
          if (this.compositions[e].data && this.compositions[e].data.nm === t) return this.compositions[e].prepareFrame && this.compositions[e].data.xt && this.compositions[e].prepareFrame(this.currentFrame), this.compositions[e].compInterface;
          e += 1;
        }

        return null;
      }

      return t.compositions = [], t.currentFrame = 0, t.registerComposition = e, t;
    };
  }(),
      EffectsExpressionInterface = function () {
    function l(s, t, e, r) {
      function i(t) {
        for (var e = s.ef, r = 0, i = e.length; r < i;) {
          if (t === e[r].nm || t === e[r].mn || t === e[r].ix) return 5 === e[r].ty ? o[r] : o[r]();
          r += 1;
        }

        throw new Error();
      }

      var a,
          n = propertyGroupFactory(i, e),
          o = [],
          h = s.ef.length;

      for (a = 0; a < h; a += 1) {
        5 === s.ef[a].ty ? o.push(l(s.ef[a], t.effectElements[a], t.effectElements[a].propertyGroup, r)) : o.push(p(t.effectElements[a], s.ef[a].ty, r, n));
      }

      return "ADBE Color Control" === s.mn && Object.defineProperty(i, "color", {
        get: function get() {
          return o[0]();
        }
      }), Object.defineProperties(i, {
        numProperties: {
          get: function get() {
            return s.np;
          }
        },
        _name: {
          value: s.nm
        },
        propertyGroup: {
          value: n
        }
      }), i.enabled = 0 !== s.en, i.active = i.enabled, i;
    }

    function p(t, e, r, i) {
      var s = ExpressionPropertyInterface(t.p);
      return t.p.setGroupProperty && t.p.setGroupProperty(PropertyInterface("", i)), function () {
        return 10 === e ? r.comp.compInterface(t.p.v) : s();
      };
    }

    return {
      createEffectsInterface: function createEffectsInterface(t, e) {
        if (t.effectsManager) {
          var r,
              i = [],
              s = t.data.ef,
              a = t.effectsManager.effectElements.length;

          for (r = 0; r < a; r += 1) {
            i.push(l(s[r], t.effectsManager.effectElements[r], e, t));
          }

          var n = t.data.ef || [],
              o = function o(t) {
            for (r = 0, a = n.length; r < a;) {
              if (t === n[r].nm || t === n[r].mn || t === n[r].ix) return i[r];
              r += 1;
            }

            return null;
          };

          return Object.defineProperty(o, "numProperties", {
            get: function get() {
              return n.length;
            }
          }), o;
        }

        return null;
      }
    };
  }(),
      MaskManagerInterface = function () {
    function t(t, e) {
      this._mask = t, this._data = e;
    }

    Object.defineProperty(t.prototype, "maskPath", {
      get: function get() {
        return this._mask.prop.k && this._mask.prop.getValue(), this._mask.prop;
      }
    }), Object.defineProperty(t.prototype, "maskOpacity", {
      get: function get() {
        return this._mask.op.k && this._mask.op.getValue(), 100 * this._mask.op.v;
      }
    });
    return function (e) {
      var r,
          i = createSizedArray(e.viewData.length),
          s = e.viewData.length;

      for (r = 0; r < s; r += 1) {
        i[r] = new t(e.viewData[r], e.masksProperties[r]);
      }

      return function (t) {
        for (r = 0; r < s;) {
          if (e.masksProperties[r].nm === t) return i[r];
          r += 1;
        }

        return null;
      };
    };
  }(),
      ExpressionPropertyInterface = function () {
    var s = {
      pv: 0,
      v: 0,
      mult: 1
    },
        n = {
      pv: [0, 0, 0],
      v: [0, 0, 0],
      mult: 1
    };

    function o(i, s, a) {
      Object.defineProperty(i, "velocity", {
        get: function get() {
          return s.getVelocityAtTime(s.comp.currentFrame);
        }
      }), i.numKeys = s.keyframes ? s.keyframes.length : 0, i.key = function (t) {
        if (!i.numKeys) return 0;
        var e = "";
        e = "s" in s.keyframes[t - 1] ? s.keyframes[t - 1].s : "e" in s.keyframes[t - 2] ? s.keyframes[t - 2].e : s.keyframes[t - 2].s;
        var r = "unidimensional" === a ? new Number(e) : Object.assign({}, e);
        return r.time = s.keyframes[t - 1].t / s.elem.comp.globalData.frameRate, r.value = "unidimensional" === a ? e[0] : e, r;
      }, i.valueAtTime = s.getValueAtTime, i.speedAtTime = s.getSpeedAtTime, i.velocityAtTime = s.getVelocityAtTime, i.propertyGroup = s.propertyGroup;
    }

    function e() {
      return s;
    }

    return function (t) {
      return t ? "unidimensional" === t.propType ? function (t) {
        t && "pv" in t || (t = s);
        var e = 1 / t.mult,
            r = t.pv * e,
            i = new Number(r);
        return i.value = r, o(i, t, "unidimensional"), function () {
          return t.k && t.getValue(), r = t.v * e, i.value !== r && ((i = new Number(r)).value = r, o(i, t, "unidimensional")), i;
        };
      }(t) : function (e) {
        e && "pv" in e || (e = n);
        var r = 1 / e.mult,
            i = e.data && e.data.l || e.pv.length,
            s = createTypedArray("float32", i),
            a = createTypedArray("float32", i);
        return s.value = a, o(s, e, "multidimensional"), function () {
          e.k && e.getValue();

          for (var t = 0; t < i; t += 1) {
            a[t] = e.v[t] * r, s[t] = a[t];
          }

          return s;
        };
      }(t) : e;
    };
  }(),
      TextExpressionSelectorPropFactory = function () {
    function r(t, e) {
      return this.textIndex = t + 1, this.textTotal = e, this.v = this.getValue() * this.mult, this.v;
    }

    return function (t, e) {
      this.pv = 1, this.comp = t.comp, this.elem = t, this.mult = .01, this.propType = "textSelector", this.textTotal = e.totalChars, this.selectorValue = 100, this.lastValue = [1, 1, 1], this.k = !0, this.x = !0, this.getValue = ExpressionManager.initiateExpression.bind(this)(t, e, this), this.getMult = r, this.getVelocityAtTime = expressionHelpers.getVelocityAtTime, this.kf ? this.getValueAtTime = expressionHelpers.getValueAtTime.bind(this) : this.getValueAtTime = expressionHelpers.getStaticValueAtTime.bind(this), this.setGroupProperty = expressionHelpers.setGroupProperty;
    };
  }(),
      propertyGetTextProp = TextSelectorProp.getTextSelectorProp;

  function SliderEffect(t, e, r) {
    this.p = PropertyFactory.getProp(e, t.v, 0, 0, r);
  }

  function AngleEffect(t, e, r) {
    this.p = PropertyFactory.getProp(e, t.v, 0, 0, r);
  }

  function ColorEffect(t, e, r) {
    this.p = PropertyFactory.getProp(e, t.v, 1, 0, r);
  }

  function PointEffect(t, e, r) {
    this.p = PropertyFactory.getProp(e, t.v, 1, 0, r);
  }

  function LayerIndexEffect(t, e, r) {
    this.p = PropertyFactory.getProp(e, t.v, 0, 0, r);
  }

  function MaskIndexEffect(t, e, r) {
    this.p = PropertyFactory.getProp(e, t.v, 0, 0, r);
  }

  function CheckboxEffect(t, e, r) {
    this.p = PropertyFactory.getProp(e, t.v, 0, 0, r);
  }

  function NoValueEffect() {
    this.p = {};
  }

  function EffectsManager(t, e) {
    var r,
        i = t.ef || [];
    this.effectElements = [];
    var s,
        a = i.length;

    for (r = 0; r < a; r += 1) {
      s = new GroupEffect(i[r], e), this.effectElements.push(s);
    }
  }

  function GroupEffect(t, e) {
    this.init(t, e);
  }

  TextSelectorProp.getTextSelectorProp = function (t, e, r) {
    return 1 === e.t ? new TextExpressionSelectorPropFactory(t, e, r) : propertyGetTextProp(t, e, r);
  }, extendPrototype([DynamicPropertyContainer], GroupEffect), GroupEffect.prototype.getValue = GroupEffect.prototype.iterateDynamicProperties, GroupEffect.prototype.init = function (t, e) {
    var r;
    this.data = t, this.effectElements = [], this.initDynamicPropertyContainer(e);
    var i,
        s = this.data.ef.length,
        a = this.data.ef;

    for (r = 0; r < s; r += 1) {
      switch (i = null, a[r].ty) {
        case 0:
          i = new SliderEffect(a[r], e, this);
          break;

        case 1:
          i = new AngleEffect(a[r], e, this);
          break;

        case 2:
          i = new ColorEffect(a[r], e, this);
          break;

        case 3:
          i = new PointEffect(a[r], e, this);
          break;

        case 4:
        case 7:
          i = new CheckboxEffect(a[r], e, this);
          break;

        case 10:
          i = new LayerIndexEffect(a[r], e, this);
          break;

        case 11:
          i = new MaskIndexEffect(a[r], e, this);
          break;

        case 5:
          i = new EffectsManager(a[r], e, this);
          break;

        default:
          i = new NoValueEffect(a[r], e, this);
      }

      i && this.effectElements.push(i);
    }
  };
  var lottie = {};

  function setLocationHref(t) {
    locationHref = t;
  }

  function searchAnimations() {
    !0 === standalone ? animationManager.searchAnimations(animationData, standalone, renderer) : animationManager.searchAnimations();
  }

  function setSubframeRendering(t) {
    subframeEnabled = t;
  }

  function setIDPrefix(t) {
    idPrefix = t;
  }

  function loadAnimation(t) {
    return !0 === standalone && (t.animationData = JSON.parse(animationData)), animationManager.loadAnimation(t);
  }

  function setQuality(t) {
    if ("string" == typeof t) switch (t) {
      case "high":
        defaultCurveSegments = 200;
        break;

      default:
      case "medium":
        defaultCurveSegments = 50;
        break;

      case "low":
        defaultCurveSegments = 10;
    } else !isNaN(t) && 1 < t && (defaultCurveSegments = t);
    roundValues(!(50 <= defaultCurveSegments));
  }

  function inBrowser() {
    return "undefined" != typeof navigator;
  }

  function installPlugin(t, e) {
    "expressions" === t && (expressionsPlugin = e);
  }

  function getFactory(t) {
    switch (t) {
      case "propertyFactory":
        return PropertyFactory;

      case "shapePropertyFactory":
        return ShapePropertyFactory;

      case "matrix":
        return Matrix;

      default:
        return null;
    }
  }

  function checkReady() {
    "complete" === document.readyState && (clearInterval(readyStateCheckInterval), searchAnimations());
  }

  function getQueryVariable(t) {
    for (var e = queryString.split("&"), r = 0; r < e.length; r += 1) {
      var i = e[r].split("=");
      if (decodeURIComponent(i[0]) == t) return decodeURIComponent(i[1]);
    }

    return null;
  }

  lottie.play = animationManager.play, lottie.pause = animationManager.pause, lottie.setLocationHref = setLocationHref, lottie.togglePause = animationManager.togglePause, lottie.setSpeed = animationManager.setSpeed, lottie.setDirection = animationManager.setDirection, lottie.stop = animationManager.stop, lottie.searchAnimations = searchAnimations, lottie.registerAnimation = animationManager.registerAnimation, lottie.loadAnimation = loadAnimation, lottie.setSubframeRendering = setSubframeRendering, lottie.resize = animationManager.resize, lottie.goToAndStop = animationManager.goToAndStop, lottie.destroy = animationManager.destroy, lottie.setQuality = setQuality, lottie.inBrowser = inBrowser, lottie.installPlugin = installPlugin, lottie.freeze = animationManager.freeze, lottie.unfreeze = animationManager.unfreeze, lottie.setVolume = animationManager.setVolume, lottie.mute = animationManager.mute, lottie.unmute = animationManager.unmute, lottie.getRegisteredAnimations = animationManager.getRegisteredAnimations, lottie.setIDPrefix = setIDPrefix, lottie.__getFactory = getFactory, lottie.version = "5.7.13";
  var standalone = "__[STANDALONE]__",
      animationData = "__[ANIMATIONDATA]__",
      renderer = "",
      queryString;

  if (standalone) {
    var scripts = document.getElementsByTagName("script"),
        index = scripts.length - 1,
        myScript = scripts[index] || {
      src: ""
    };
    queryString = myScript.src.replace(/^[^\?]+\??/, ""), renderer = getQueryVariable("renderer");
  }

  var readyStateCheckInterval = setInterval(checkReady, 100);
  return lottie;
});
"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/**
 * Swiper 6.8.1
 * Most modern mobile touch slider and framework with hardware accelerated transitions
 * https://swiperjs.com
 *
 * Copyright 2014-2021 Vladimir Kharlampidi
 *
 * Released under the MIT License
 *
 * Released on: August 3, 2021
 */
(function (global, factory) {
  (typeof exports === "undefined" ? "undefined" : _typeof(exports)) === 'object' && typeof module !== 'undefined' ? module.exports = factory() : typeof define === 'function' && define.amd ? define(factory) : (global = typeof globalThis !== 'undefined' ? globalThis : global || self, global.Swiper = factory());
})(void 0, function () {
  'use strict';

  function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }

  function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
  }

  function _extends() {
    _extends = Object.assign || function (target) {
      for (var i = 1; i < arguments.length; i++) {
        var source = arguments[i];

        for (var key in source) {
          if (Object.prototype.hasOwnProperty.call(source, key)) {
            target[key] = source[key];
          }
        }
      }

      return target;
    };

    return _extends.apply(this, arguments);
  }
  /**
   * SSR Window 3.0.0
   * Better handling for window object in SSR environment
   * https://github.com/nolimits4web/ssr-window
   *
   * Copyright 2020, Vladimir Kharlampidi
   *
   * Licensed under MIT
   *
   * Released on: November 9, 2020
   */

  /* eslint-disable no-param-reassign */


  function isObject$1(obj) {
    return obj !== null && _typeof(obj) === 'object' && 'constructor' in obj && obj.constructor === Object;
  }

  function extend$1(target, src) {
    if (target === void 0) {
      target = {};
    }

    if (src === void 0) {
      src = {};
    }

    Object.keys(src).forEach(function (key) {
      if (typeof target[key] === 'undefined') target[key] = src[key];else if (isObject$1(src[key]) && isObject$1(target[key]) && Object.keys(src[key]).length > 0) {
        extend$1(target[key], src[key]);
      }
    });
  }

  var ssrDocument = {
    body: {},
    addEventListener: function addEventListener() {},
    removeEventListener: function removeEventListener() {},
    activeElement: {
      blur: function blur() {},
      nodeName: ''
    },
    querySelector: function querySelector() {
      return null;
    },
    querySelectorAll: function querySelectorAll() {
      return [];
    },
    getElementById: function getElementById() {
      return null;
    },
    createEvent: function createEvent() {
      return {
        initEvent: function initEvent() {}
      };
    },
    createElement: function createElement() {
      return {
        children: [],
        childNodes: [],
        style: {},
        setAttribute: function setAttribute() {},
        getElementsByTagName: function getElementsByTagName() {
          return [];
        }
      };
    },
    createElementNS: function createElementNS() {
      return {};
    },
    importNode: function importNode() {
      return null;
    },
    location: {
      hash: '',
      host: '',
      hostname: '',
      href: '',
      origin: '',
      pathname: '',
      protocol: '',
      search: ''
    }
  };

  function getDocument() {
    var doc = typeof document !== 'undefined' ? document : {};
    extend$1(doc, ssrDocument);
    return doc;
  }

  var ssrWindow = {
    document: ssrDocument,
    navigator: {
      userAgent: ''
    },
    location: {
      hash: '',
      host: '',
      hostname: '',
      href: '',
      origin: '',
      pathname: '',
      protocol: '',
      search: ''
    },
    history: {
      replaceState: function replaceState() {},
      pushState: function pushState() {},
      go: function go() {},
      back: function back() {}
    },
    CustomEvent: function CustomEvent() {
      return this;
    },
    addEventListener: function addEventListener() {},
    removeEventListener: function removeEventListener() {},
    getComputedStyle: function getComputedStyle() {
      return {
        getPropertyValue: function getPropertyValue() {
          return '';
        }
      };
    },
    Image: function Image() {},
    Date: function Date() {},
    screen: {},
    setTimeout: function setTimeout() {},
    clearTimeout: function clearTimeout() {},
    matchMedia: function matchMedia() {
      return {};
    },
    requestAnimationFrame: function requestAnimationFrame(callback) {
      if (typeof setTimeout === 'undefined') {
        callback();
        return null;
      }

      return setTimeout(callback, 0);
    },
    cancelAnimationFrame: function cancelAnimationFrame(id) {
      if (typeof setTimeout === 'undefined') {
        return;
      }

      clearTimeout(id);
    }
  };

  function getWindow() {
    var win = typeof window !== 'undefined' ? window : {};
    extend$1(win, ssrWindow);
    return win;
  }
  /**
   * Dom7 3.0.0
   * Minimalistic JavaScript library for DOM manipulation, with a jQuery-compatible API
   * https://framework7.io/docs/dom7.html
   *
   * Copyright 2020, Vladimir Kharlampidi
   *
   * Licensed under MIT
   *
   * Released on: November 9, 2020
   */


  function _inheritsLoose(subClass, superClass) {
    subClass.prototype = Object.create(superClass.prototype);
    subClass.prototype.constructor = subClass;
    subClass.__proto__ = superClass;
  }

  function _getPrototypeOf(o) {
    _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
      return o.__proto__ || Object.getPrototypeOf(o);
    };
    return _getPrototypeOf(o);
  }

  function _setPrototypeOf(o, p) {
    _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
      o.__proto__ = p;
      return o;
    };

    return _setPrototypeOf(o, p);
  }

  function _isNativeReflectConstruct() {
    if (typeof Reflect === "undefined" || !Reflect.construct) return false;
    if (Reflect.construct.sham) return false;
    if (typeof Proxy === "function") return true;

    try {
      Date.prototype.toString.call(Reflect.construct(Date, [], function () {}));
      return true;
    } catch (e) {
      return false;
    }
  }

  function _construct(Parent, args, Class) {
    if (_isNativeReflectConstruct()) {
      _construct = Reflect.construct;
    } else {
      _construct = function _construct(Parent, args, Class) {
        var a = [null];
        a.push.apply(a, args);
        var Constructor = Function.bind.apply(Parent, a);
        var instance = new Constructor();
        if (Class) _setPrototypeOf(instance, Class.prototype);
        return instance;
      };
    }

    return _construct.apply(null, arguments);
  }

  function _isNativeFunction(fn) {
    return Function.toString.call(fn).indexOf("[native code]") !== -1;
  }

  function _wrapNativeSuper(Class) {
    var _cache = typeof Map === "function" ? new Map() : undefined;

    _wrapNativeSuper = function _wrapNativeSuper(Class) {
      if (Class === null || !_isNativeFunction(Class)) return Class;

      if (typeof Class !== "function") {
        throw new TypeError("Super expression must either be null or a function");
      }

      if (typeof _cache !== "undefined") {
        if (_cache.has(Class)) return _cache.get(Class);

        _cache.set(Class, Wrapper);
      }

      function Wrapper() {
        return _construct(Class, arguments, _getPrototypeOf(this).constructor);
      }

      Wrapper.prototype = Object.create(Class.prototype, {
        constructor: {
          value: Wrapper,
          enumerable: false,
          writable: true,
          configurable: true
        }
      });
      return _setPrototypeOf(Wrapper, Class);
    };

    return _wrapNativeSuper(Class);
  }

  function _assertThisInitialized(self) {
    if (self === void 0) {
      throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
    }

    return self;
  }
  /* eslint-disable no-proto */


  function makeReactive(obj) {
    var proto = obj.__proto__;
    Object.defineProperty(obj, '__proto__', {
      get: function get() {
        return proto;
      },
      set: function set(value) {
        proto.__proto__ = value;
      }
    });
  }

  var Dom7 = /*#__PURE__*/function (_Array) {
    _inheritsLoose(Dom7, _Array);

    function Dom7(items) {
      var _this;

      _this = _Array.call.apply(_Array, [this].concat(items)) || this;
      makeReactive(_assertThisInitialized(_this));
      return _this;
    }

    return Dom7;
  }( /*#__PURE__*/_wrapNativeSuper(Array));

  function arrayFlat(arr) {
    if (arr === void 0) {
      arr = [];
    }

    var res = [];
    arr.forEach(function (el) {
      if (Array.isArray(el)) {
        res.push.apply(res, arrayFlat(el));
      } else {
        res.push(el);
      }
    });
    return res;
  }

  function arrayFilter(arr, callback) {
    return Array.prototype.filter.call(arr, callback);
  }

  function arrayUnique(arr) {
    var uniqueArray = [];

    for (var i = 0; i < arr.length; i += 1) {
      if (uniqueArray.indexOf(arr[i]) === -1) uniqueArray.push(arr[i]);
    }

    return uniqueArray;
  }

  function qsa(selector, context) {
    if (typeof selector !== 'string') {
      return [selector];
    }

    var a = [];
    var res = context.querySelectorAll(selector);

    for (var i = 0; i < res.length; i += 1) {
      a.push(res[i]);
    }

    return a;
  }

  function $(selector, context) {
    var window = getWindow();
    var document = getDocument();
    var arr = [];

    if (!context && selector instanceof Dom7) {
      return selector;
    }

    if (!selector) {
      return new Dom7(arr);
    }

    if (typeof selector === 'string') {
      var html = selector.trim();

      if (html.indexOf('<') >= 0 && html.indexOf('>') >= 0) {
        var toCreate = 'div';
        if (html.indexOf('<li') === 0) toCreate = 'ul';
        if (html.indexOf('<tr') === 0) toCreate = 'tbody';
        if (html.indexOf('<td') === 0 || html.indexOf('<th') === 0) toCreate = 'tr';
        if (html.indexOf('<tbody') === 0) toCreate = 'table';
        if (html.indexOf('<option') === 0) toCreate = 'select';
        var tempParent = document.createElement(toCreate);
        tempParent.innerHTML = html;

        for (var i = 0; i < tempParent.childNodes.length; i += 1) {
          arr.push(tempParent.childNodes[i]);
        }
      } else {
        arr = qsa(selector.trim(), context || document);
      } // arr = qsa(selector, document);

    } else if (selector.nodeType || selector === window || selector === document) {
      arr.push(selector);
    } else if (Array.isArray(selector)) {
      if (selector instanceof Dom7) return selector;
      arr = selector;
    }

    return new Dom7(arrayUnique(arr));
  }

  $.fn = Dom7.prototype;

  function addClass() {
    for (var _len = arguments.length, classes = new Array(_len), _key = 0; _key < _len; _key++) {
      classes[_key] = arguments[_key];
    }

    var classNames = arrayFlat(classes.map(function (c) {
      return c.split(' ');
    }));
    this.forEach(function (el) {
      var _el$classList;

      (_el$classList = el.classList).add.apply(_el$classList, classNames);
    });
    return this;
  }

  function removeClass() {
    for (var _len2 = arguments.length, classes = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
      classes[_key2] = arguments[_key2];
    }

    var classNames = arrayFlat(classes.map(function (c) {
      return c.split(' ');
    }));
    this.forEach(function (el) {
      var _el$classList2;

      (_el$classList2 = el.classList).remove.apply(_el$classList2, classNames);
    });
    return this;
  }

  function toggleClass() {
    for (var _len3 = arguments.length, classes = new Array(_len3), _key3 = 0; _key3 < _len3; _key3++) {
      classes[_key3] = arguments[_key3];
    }

    var classNames = arrayFlat(classes.map(function (c) {
      return c.split(' ');
    }));
    this.forEach(function (el) {
      classNames.forEach(function (className) {
        el.classList.toggle(className);
      });
    });
  }

  function hasClass() {
    for (var _len4 = arguments.length, classes = new Array(_len4), _key4 = 0; _key4 < _len4; _key4++) {
      classes[_key4] = arguments[_key4];
    }

    var classNames = arrayFlat(classes.map(function (c) {
      return c.split(' ');
    }));
    return arrayFilter(this, function (el) {
      return classNames.filter(function (className) {
        return el.classList.contains(className);
      }).length > 0;
    }).length > 0;
  }

  function attr(attrs, value) {
    if (arguments.length === 1 && typeof attrs === 'string') {
      // Get attr
      if (this[0]) return this[0].getAttribute(attrs);
      return undefined;
    } // Set attrs


    for (var i = 0; i < this.length; i += 1) {
      if (arguments.length === 2) {
        // String
        this[i].setAttribute(attrs, value);
      } else {
        // Object
        for (var attrName in attrs) {
          this[i][attrName] = attrs[attrName];
          this[i].setAttribute(attrName, attrs[attrName]);
        }
      }
    }

    return this;
  }

  function removeAttr(attr) {
    for (var i = 0; i < this.length; i += 1) {
      this[i].removeAttribute(attr);
    }

    return this;
  }

  function transform(transform) {
    for (var i = 0; i < this.length; i += 1) {
      this[i].style.transform = transform;
    }

    return this;
  }

  function transition$1(duration) {
    for (var i = 0; i < this.length; i += 1) {
      this[i].style.transitionDuration = typeof duration !== 'string' ? duration + "ms" : duration;
    }

    return this;
  }

  function on() {
    for (var _len5 = arguments.length, args = new Array(_len5), _key5 = 0; _key5 < _len5; _key5++) {
      args[_key5] = arguments[_key5];
    }

    var eventType = args[0],
        targetSelector = args[1],
        listener = args[2],
        capture = args[3];

    if (typeof args[1] === 'function') {
      eventType = args[0];
      listener = args[1];
      capture = args[2];
      targetSelector = undefined;
    }

    if (!capture) capture = false;

    function handleLiveEvent(e) {
      var target = e.target;
      if (!target) return;
      var eventData = e.target.dom7EventData || [];

      if (eventData.indexOf(e) < 0) {
        eventData.unshift(e);
      }

      if ($(target).is(targetSelector)) listener.apply(target, eventData);else {
        var _parents = $(target).parents(); // eslint-disable-line


        for (var k = 0; k < _parents.length; k += 1) {
          if ($(_parents[k]).is(targetSelector)) listener.apply(_parents[k], eventData);
        }
      }
    }

    function handleEvent(e) {
      var eventData = e && e.target ? e.target.dom7EventData || [] : [];

      if (eventData.indexOf(e) < 0) {
        eventData.unshift(e);
      }

      listener.apply(this, eventData);
    }

    var events = eventType.split(' ');
    var j;

    for (var i = 0; i < this.length; i += 1) {
      var el = this[i];

      if (!targetSelector) {
        for (j = 0; j < events.length; j += 1) {
          var event = events[j];
          if (!el.dom7Listeners) el.dom7Listeners = {};
          if (!el.dom7Listeners[event]) el.dom7Listeners[event] = [];
          el.dom7Listeners[event].push({
            listener: listener,
            proxyListener: handleEvent
          });
          el.addEventListener(event, handleEvent, capture);
        }
      } else {
        // Live events
        for (j = 0; j < events.length; j += 1) {
          var _event = events[j];
          if (!el.dom7LiveListeners) el.dom7LiveListeners = {};
          if (!el.dom7LiveListeners[_event]) el.dom7LiveListeners[_event] = [];

          el.dom7LiveListeners[_event].push({
            listener: listener,
            proxyListener: handleLiveEvent
          });

          el.addEventListener(_event, handleLiveEvent, capture);
        }
      }
    }

    return this;
  }

  function off() {
    for (var _len6 = arguments.length, args = new Array(_len6), _key6 = 0; _key6 < _len6; _key6++) {
      args[_key6] = arguments[_key6];
    }

    var eventType = args[0],
        targetSelector = args[1],
        listener = args[2],
        capture = args[3];

    if (typeof args[1] === 'function') {
      eventType = args[0];
      listener = args[1];
      capture = args[2];
      targetSelector = undefined;
    }

    if (!capture) capture = false;
    var events = eventType.split(' ');

    for (var i = 0; i < events.length; i += 1) {
      var event = events[i];

      for (var j = 0; j < this.length; j += 1) {
        var el = this[j];
        var handlers = void 0;

        if (!targetSelector && el.dom7Listeners) {
          handlers = el.dom7Listeners[event];
        } else if (targetSelector && el.dom7LiveListeners) {
          handlers = el.dom7LiveListeners[event];
        }

        if (handlers && handlers.length) {
          for (var k = handlers.length - 1; k >= 0; k -= 1) {
            var handler = handlers[k];

            if (listener && handler.listener === listener) {
              el.removeEventListener(event, handler.proxyListener, capture);
              handlers.splice(k, 1);
            } else if (listener && handler.listener && handler.listener.dom7proxy && handler.listener.dom7proxy === listener) {
              el.removeEventListener(event, handler.proxyListener, capture);
              handlers.splice(k, 1);
            } else if (!listener) {
              el.removeEventListener(event, handler.proxyListener, capture);
              handlers.splice(k, 1);
            }
          }
        }
      }
    }

    return this;
  }

  function trigger() {
    var window = getWindow();

    for (var _len9 = arguments.length, args = new Array(_len9), _key9 = 0; _key9 < _len9; _key9++) {
      args[_key9] = arguments[_key9];
    }

    var events = args[0].split(' ');
    var eventData = args[1];

    for (var i = 0; i < events.length; i += 1) {
      var event = events[i];

      for (var j = 0; j < this.length; j += 1) {
        var el = this[j];

        if (window.CustomEvent) {
          var evt = new window.CustomEvent(event, {
            detail: eventData,
            bubbles: true,
            cancelable: true
          });
          el.dom7EventData = args.filter(function (data, dataIndex) {
            return dataIndex > 0;
          });
          el.dispatchEvent(evt);
          el.dom7EventData = [];
          delete el.dom7EventData;
        }
      }
    }

    return this;
  }

  function transitionEnd$1(callback) {
    var dom = this;

    function fireCallBack(e) {
      if (e.target !== this) return;
      callback.call(this, e);
      dom.off('transitionend', fireCallBack);
    }

    if (callback) {
      dom.on('transitionend', fireCallBack);
    }

    return this;
  }

  function outerWidth(includeMargins) {
    if (this.length > 0) {
      if (includeMargins) {
        var _styles = this.styles();

        return this[0].offsetWidth + parseFloat(_styles.getPropertyValue('margin-right')) + parseFloat(_styles.getPropertyValue('margin-left'));
      }

      return this[0].offsetWidth;
    }

    return null;
  }

  function outerHeight(includeMargins) {
    if (this.length > 0) {
      if (includeMargins) {
        var _styles2 = this.styles();

        return this[0].offsetHeight + parseFloat(_styles2.getPropertyValue('margin-top')) + parseFloat(_styles2.getPropertyValue('margin-bottom'));
      }

      return this[0].offsetHeight;
    }

    return null;
  }

  function offset() {
    if (this.length > 0) {
      var window = getWindow();
      var document = getDocument();
      var el = this[0];
      var box = el.getBoundingClientRect();
      var body = document.body;
      var clientTop = el.clientTop || body.clientTop || 0;
      var clientLeft = el.clientLeft || body.clientLeft || 0;
      var scrollTop = el === window ? window.scrollY : el.scrollTop;
      var scrollLeft = el === window ? window.scrollX : el.scrollLeft;
      return {
        top: box.top + scrollTop - clientTop,
        left: box.left + scrollLeft - clientLeft
      };
    }

    return null;
  }

  function styles() {
    var window = getWindow();
    if (this[0]) return window.getComputedStyle(this[0], null);
    return {};
  }

  function css(props, value) {
    var window = getWindow();
    var i;

    if (arguments.length === 1) {
      if (typeof props === 'string') {
        // .css('width')
        if (this[0]) return window.getComputedStyle(this[0], null).getPropertyValue(props);
      } else {
        // .css({ width: '100px' })
        for (i = 0; i < this.length; i += 1) {
          for (var _prop in props) {
            this[i].style[_prop] = props[_prop];
          }
        }

        return this;
      }
    }

    if (arguments.length === 2 && typeof props === 'string') {
      // .css('width', '100px')
      for (i = 0; i < this.length; i += 1) {
        this[i].style[props] = value;
      }

      return this;
    }

    return this;
  }

  function each(callback) {
    if (!callback) return this;
    this.forEach(function (el, index) {
      callback.apply(el, [el, index]);
    });
    return this;
  }

  function filter(callback) {
    var result = arrayFilter(this, callback);
    return $(result);
  }

  function html(html) {
    if (typeof html === 'undefined') {
      return this[0] ? this[0].innerHTML : null;
    }

    for (var i = 0; i < this.length; i += 1) {
      this[i].innerHTML = html;
    }

    return this;
  }

  function text(text) {
    if (typeof text === 'undefined') {
      return this[0] ? this[0].textContent.trim() : null;
    }

    for (var i = 0; i < this.length; i += 1) {
      this[i].textContent = text;
    }

    return this;
  }

  function is(selector) {
    var window = getWindow();
    var document = getDocument();
    var el = this[0];
    var compareWith;
    var i;
    if (!el || typeof selector === 'undefined') return false;

    if (typeof selector === 'string') {
      if (el.matches) return el.matches(selector);
      if (el.webkitMatchesSelector) return el.webkitMatchesSelector(selector);
      if (el.msMatchesSelector) return el.msMatchesSelector(selector);
      compareWith = $(selector);

      for (i = 0; i < compareWith.length; i += 1) {
        if (compareWith[i] === el) return true;
      }

      return false;
    }

    if (selector === document) {
      return el === document;
    }

    if (selector === window) {
      return el === window;
    }

    if (selector.nodeType || selector instanceof Dom7) {
      compareWith = selector.nodeType ? [selector] : selector;

      for (i = 0; i < compareWith.length; i += 1) {
        if (compareWith[i] === el) return true;
      }

      return false;
    }

    return false;
  }

  function index() {
    var child = this[0];
    var i;

    if (child) {
      i = 0; // eslint-disable-next-line

      while ((child = child.previousSibling) !== null) {
        if (child.nodeType === 1) i += 1;
      }

      return i;
    }

    return undefined;
  }

  function eq(index) {
    if (typeof index === 'undefined') return this;
    var length = this.length;

    if (index > length - 1) {
      return $([]);
    }

    if (index < 0) {
      var returnIndex = length + index;
      if (returnIndex < 0) return $([]);
      return $([this[returnIndex]]);
    }

    return $([this[index]]);
  }

  function append() {
    var newChild;
    var document = getDocument();

    for (var k = 0; k < arguments.length; k += 1) {
      newChild = k < 0 || arguments.length <= k ? undefined : arguments[k];

      for (var i = 0; i < this.length; i += 1) {
        if (typeof newChild === 'string') {
          var tempDiv = document.createElement('div');
          tempDiv.innerHTML = newChild;

          while (tempDiv.firstChild) {
            this[i].appendChild(tempDiv.firstChild);
          }
        } else if (newChild instanceof Dom7) {
          for (var j = 0; j < newChild.length; j += 1) {
            this[i].appendChild(newChild[j]);
          }
        } else {
          this[i].appendChild(newChild);
        }
      }
    }

    return this;
  }

  function prepend(newChild) {
    var document = getDocument();
    var i;
    var j;

    for (i = 0; i < this.length; i += 1) {
      if (typeof newChild === 'string') {
        var tempDiv = document.createElement('div');
        tempDiv.innerHTML = newChild;

        for (j = tempDiv.childNodes.length - 1; j >= 0; j -= 1) {
          this[i].insertBefore(tempDiv.childNodes[j], this[i].childNodes[0]);
        }
      } else if (newChild instanceof Dom7) {
        for (j = 0; j < newChild.length; j += 1) {
          this[i].insertBefore(newChild[j], this[i].childNodes[0]);
        }
      } else {
        this[i].insertBefore(newChild, this[i].childNodes[0]);
      }
    }

    return this;
  }

  function next(selector) {
    if (this.length > 0) {
      if (selector) {
        if (this[0].nextElementSibling && $(this[0].nextElementSibling).is(selector)) {
          return $([this[0].nextElementSibling]);
        }

        return $([]);
      }

      if (this[0].nextElementSibling) return $([this[0].nextElementSibling]);
      return $([]);
    }

    return $([]);
  }

  function nextAll(selector) {
    var nextEls = [];
    var el = this[0];
    if (!el) return $([]);

    while (el.nextElementSibling) {
      var _next = el.nextElementSibling; // eslint-disable-line

      if (selector) {
        if ($(_next).is(selector)) nextEls.push(_next);
      } else nextEls.push(_next);

      el = _next;
    }

    return $(nextEls);
  }

  function prev(selector) {
    if (this.length > 0) {
      var el = this[0];

      if (selector) {
        if (el.previousElementSibling && $(el.previousElementSibling).is(selector)) {
          return $([el.previousElementSibling]);
        }

        return $([]);
      }

      if (el.previousElementSibling) return $([el.previousElementSibling]);
      return $([]);
    }

    return $([]);
  }

  function prevAll(selector) {
    var prevEls = [];
    var el = this[0];
    if (!el) return $([]);

    while (el.previousElementSibling) {
      var _prev = el.previousElementSibling; // eslint-disable-line

      if (selector) {
        if ($(_prev).is(selector)) prevEls.push(_prev);
      } else prevEls.push(_prev);

      el = _prev;
    }

    return $(prevEls);
  }

  function parent(selector) {
    var parents = []; // eslint-disable-line

    for (var i = 0; i < this.length; i += 1) {
      if (this[i].parentNode !== null) {
        if (selector) {
          if ($(this[i].parentNode).is(selector)) parents.push(this[i].parentNode);
        } else {
          parents.push(this[i].parentNode);
        }
      }
    }

    return $(parents);
  }

  function parents(selector) {
    var parents = []; // eslint-disable-line

    for (var i = 0; i < this.length; i += 1) {
      var _parent = this[i].parentNode; // eslint-disable-line

      while (_parent) {
        if (selector) {
          if ($(_parent).is(selector)) parents.push(_parent);
        } else {
          parents.push(_parent);
        }

        _parent = _parent.parentNode;
      }
    }

    return $(parents);
  }

  function closest(selector) {
    var closest = this; // eslint-disable-line

    if (typeof selector === 'undefined') {
      return $([]);
    }

    if (!closest.is(selector)) {
      closest = closest.parents(selector).eq(0);
    }

    return closest;
  }

  function find(selector) {
    var foundElements = [];

    for (var i = 0; i < this.length; i += 1) {
      try {
        var found = this[i].querySelectorAll(selector);
      } catch (err) {
        console.log(selector);
      }

      for (var j = 0; j < found.length; j += 1) {
        foundElements.push(found[j]);
      }
    }

    return $(foundElements);
  }

  function children(selector) {
    var children = []; // eslint-disable-line

    for (var i = 0; i < this.length; i += 1) {
      var childNodes = this[i].children;

      for (var j = 0; j < childNodes.length; j += 1) {
        if (!selector || $(childNodes[j]).is(selector)) {
          children.push(childNodes[j]);
        }
      }
    }

    return $(children);
  }

  function remove() {
    for (var i = 0; i < this.length; i += 1) {
      if (this[i].parentNode) this[i].parentNode.removeChild(this[i]);
    }

    return this;
  }

  var Methods = {
    addClass: addClass,
    removeClass: removeClass,
    hasClass: hasClass,
    toggleClass: toggleClass,
    attr: attr,
    removeAttr: removeAttr,
    transform: transform,
    transition: transition$1,
    on: on,
    off: off,
    trigger: trigger,
    transitionEnd: transitionEnd$1,
    outerWidth: outerWidth,
    outerHeight: outerHeight,
    styles: styles,
    offset: offset,
    css: css,
    each: each,
    html: html,
    text: text,
    is: is,
    index: index,
    eq: eq,
    append: append,
    prepend: prepend,
    next: next,
    nextAll: nextAll,
    prev: prev,
    prevAll: prevAll,
    parent: parent,
    parents: parents,
    closest: closest,
    find: find,
    children: children,
    filter: filter,
    remove: remove
  };
  Object.keys(Methods).forEach(function (methodName) {
    Object.defineProperty($.fn, methodName, {
      value: Methods[methodName],
      writable: true
    });
  });

  function deleteProps(obj) {
    var object = obj;
    Object.keys(object).forEach(function (key) {
      try {
        object[key] = null;
      } catch (e) {// no getter for object
      }

      try {
        delete object[key];
      } catch (e) {// something got wrong
      }
    });
  }

  function nextTick(callback, delay) {
    if (delay === void 0) {
      delay = 0;
    }

    return setTimeout(callback, delay);
  }

  function now() {
    return Date.now();
  }

  function getComputedStyle$1(el) {
    var window = getWindow();
    var style;

    if (window.getComputedStyle) {
      style = window.getComputedStyle(el, null);
    }

    if (!style && el.currentStyle) {
      style = el.currentStyle;
    }

    if (!style) {
      style = el.style;
    }

    return style;
  }

  function getTranslate(el, axis) {
    if (axis === void 0) {
      axis = 'x';
    }

    var window = getWindow();
    var matrix;
    var curTransform;
    var transformMatrix;
    var curStyle = getComputedStyle$1(el);

    if (window.WebKitCSSMatrix) {
      curTransform = curStyle.transform || curStyle.webkitTransform;

      if (curTransform.split(',').length > 6) {
        curTransform = curTransform.split(', ').map(function (a) {
          return a.replace(',', '.');
        }).join(', ');
      } // Some old versions of Webkit choke when 'none' is passed; pass
      // empty string instead in this case


      transformMatrix = new window.WebKitCSSMatrix(curTransform === 'none' ? '' : curTransform);
    } else {
      transformMatrix = curStyle.MozTransform || curStyle.OTransform || curStyle.MsTransform || curStyle.msTransform || curStyle.transform || curStyle.getPropertyValue('transform').replace('translate(', 'matrix(1, 0, 0, 1,');
      matrix = transformMatrix.toString().split(',');
    }

    if (axis === 'x') {
      // Latest Chrome and webkits Fix
      if (window.WebKitCSSMatrix) curTransform = transformMatrix.m41; // Crazy IE10 Matrix
      else if (matrix.length === 16) curTransform = parseFloat(matrix[12]); // Normal Browsers
        else curTransform = parseFloat(matrix[4]);
    }

    if (axis === 'y') {
      // Latest Chrome and webkits Fix
      if (window.WebKitCSSMatrix) curTransform = transformMatrix.m42; // Crazy IE10 Matrix
      else if (matrix.length === 16) curTransform = parseFloat(matrix[13]); // Normal Browsers
        else curTransform = parseFloat(matrix[5]);
    }

    return curTransform || 0;
  }

  function isObject(o) {
    return _typeof(o) === 'object' && o !== null && o.constructor && Object.prototype.toString.call(o).slice(8, -1) === 'Object';
  }

  function isNode(node) {
    // eslint-disable-next-line
    if (typeof window !== 'undefined' && typeof window.HTMLElement !== 'undefined') {
      return node instanceof HTMLElement;
    }

    return node && (node.nodeType === 1 || node.nodeType === 11);
  }

  function extend() {
    var to = Object(arguments.length <= 0 ? undefined : arguments[0]);
    var noExtend = ['__proto__', 'constructor', 'prototype'];

    for (var i = 1; i < arguments.length; i += 1) {
      var nextSource = i < 0 || arguments.length <= i ? undefined : arguments[i];

      if (nextSource !== undefined && nextSource !== null && !isNode(nextSource)) {
        var keysArray = Object.keys(Object(nextSource)).filter(function (key) {
          return noExtend.indexOf(key) < 0;
        });

        for (var nextIndex = 0, len = keysArray.length; nextIndex < len; nextIndex += 1) {
          var nextKey = keysArray[nextIndex];
          var desc = Object.getOwnPropertyDescriptor(nextSource, nextKey);

          if (desc !== undefined && desc.enumerable) {
            if (isObject(to[nextKey]) && isObject(nextSource[nextKey])) {
              if (nextSource[nextKey].__swiper__) {
                to[nextKey] = nextSource[nextKey];
              } else {
                extend(to[nextKey], nextSource[nextKey]);
              }
            } else if (!isObject(to[nextKey]) && isObject(nextSource[nextKey])) {
              to[nextKey] = {};

              if (nextSource[nextKey].__swiper__) {
                to[nextKey] = nextSource[nextKey];
              } else {
                extend(to[nextKey], nextSource[nextKey]);
              }
            } else {
              to[nextKey] = nextSource[nextKey];
            }
          }
        }
      }
    }

    return to;
  }

  function bindModuleMethods(instance, obj) {
    Object.keys(obj).forEach(function (key) {
      if (isObject(obj[key])) {
        Object.keys(obj[key]).forEach(function (subKey) {
          if (typeof obj[key][subKey] === 'function') {
            obj[key][subKey] = obj[key][subKey].bind(instance);
          }
        });
      }

      instance[key] = obj[key];
    });
  }

  function classesToSelector(classes) {
    if (classes === void 0) {
      classes = '';
    }

    return "." + classes.trim().replace(/([\.:\/])/g, '\\$1') // eslint-disable-line
    .replace(/ /g, '.');
  }

  function createElementIfNotDefined($container, params, createElements, checkProps) {
    var document = getDocument();

    if (createElements) {
      Object.keys(checkProps).forEach(function (key) {
        if (!params[key] && params.auto === true) {
          var element = document.createElement('div');
          element.className = checkProps[key];
          $container.append(element);
          params[key] = element;
        }
      });
    }

    return params;
  }

  var support;

  function calcSupport() {
    var window = getWindow();
    var document = getDocument();
    return {
      touch: !!('ontouchstart' in window || window.DocumentTouch && document instanceof window.DocumentTouch),
      pointerEvents: !!window.PointerEvent && 'maxTouchPoints' in window.navigator && window.navigator.maxTouchPoints >= 0,
      observer: function checkObserver() {
        return 'MutationObserver' in window || 'WebkitMutationObserver' in window;
      }(),
      passiveListener: function checkPassiveListener() {
        var supportsPassive = false;

        try {
          var opts = Object.defineProperty({}, 'passive', {
            // eslint-disable-next-line
            get: function get() {
              supportsPassive = true;
            }
          });
          window.addEventListener('testPassiveListener', null, opts);
        } catch (e) {// No support
        }

        return supportsPassive;
      }(),
      gestures: function checkGestures() {
        return 'ongesturestart' in window;
      }()
    };
  }

  function getSupport() {
    if (!support) {
      support = calcSupport();
    }

    return support;
  }

  var device;

  function calcDevice(_temp) {
    var _ref = _temp === void 0 ? {} : _temp,
        userAgent = _ref.userAgent;

    var support = getSupport();
    var window = getWindow();
    var platform = window.navigator.platform;
    var ua = userAgent || window.navigator.userAgent;
    var device = {
      ios: false,
      android: false
    };
    var screenWidth = window.screen.width;
    var screenHeight = window.screen.height;
    var android = ua.match(/(Android);?[\s\/]+([\d.]+)?/); // eslint-disable-line

    var ipad = ua.match(/(iPad).*OS\s([\d_]+)/);
    var ipod = ua.match(/(iPod)(.*OS\s([\d_]+))?/);
    var iphone = !ipad && ua.match(/(iPhone\sOS|iOS)\s([\d_]+)/);
    var windows = platform === 'Win32';
    var macos = platform === 'MacIntel'; // iPadOs 13 fix

    var iPadScreens = ['1024x1366', '1366x1024', '834x1194', '1194x834', '834x1112', '1112x834', '768x1024', '1024x768', '820x1180', '1180x820', '810x1080', '1080x810'];

    if (!ipad && macos && support.touch && iPadScreens.indexOf(screenWidth + "x" + screenHeight) >= 0) {
      ipad = ua.match(/(Version)\/([\d.]+)/);
      if (!ipad) ipad = [0, 1, '13_0_0'];
      macos = false;
    } // Android


    if (android && !windows) {
      device.os = 'android';
      device.android = true;
    }

    if (ipad || iphone || ipod) {
      device.os = 'ios';
      device.ios = true;
    } // Export object


    return device;
  }

  function getDevice(overrides) {
    if (overrides === void 0) {
      overrides = {};
    }

    if (!device) {
      device = calcDevice(overrides);
    }

    return device;
  }

  var browser;

  function calcBrowser() {
    var window = getWindow();

    function isSafari() {
      var ua = window.navigator.userAgent.toLowerCase();
      return ua.indexOf('safari') >= 0 && ua.indexOf('chrome') < 0 && ua.indexOf('android') < 0;
    }

    return {
      isEdge: !!window.navigator.userAgent.match(/Edge/g),
      isSafari: isSafari(),
      isWebView: /(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(window.navigator.userAgent)
    };
  }

  function getBrowser() {
    if (!browser) {
      browser = calcBrowser();
    }

    return browser;
  }

  var supportsResizeObserver = function supportsResizeObserver() {
    var window = getWindow();
    return typeof window.ResizeObserver !== 'undefined';
  };

  var Resize = {
    name: 'resize',
    create: function create() {
      var swiper = this;
      extend(swiper, {
        resize: {
          observer: null,
          createObserver: function createObserver() {
            if (!swiper || swiper.destroyed || !swiper.initialized) return;
            swiper.resize.observer = new ResizeObserver(function (entries) {
              var width = swiper.width,
                  height = swiper.height;
              var newWidth = width;
              var newHeight = height;
              entries.forEach(function (_ref) {
                var contentBoxSize = _ref.contentBoxSize,
                    contentRect = _ref.contentRect,
                    target = _ref.target;
                if (target && target !== swiper.el) return;
                newWidth = contentRect ? contentRect.width : (contentBoxSize[0] || contentBoxSize).inlineSize;
                newHeight = contentRect ? contentRect.height : (contentBoxSize[0] || contentBoxSize).blockSize;
              });

              if (newWidth !== width || newHeight !== height) {
                swiper.resize.resizeHandler();
              }
            });
            swiper.resize.observer.observe(swiper.el);
          },
          removeObserver: function removeObserver() {
            if (swiper.resize.observer && swiper.resize.observer.unobserve && swiper.el) {
              swiper.resize.observer.unobserve(swiper.el);
              swiper.resize.observer = null;
            }
          },
          resizeHandler: function resizeHandler() {
            if (!swiper || swiper.destroyed || !swiper.initialized) return;
            swiper.emit('beforeResize');
            swiper.emit('resize');
          },
          orientationChangeHandler: function orientationChangeHandler() {
            if (!swiper || swiper.destroyed || !swiper.initialized) return;
            swiper.emit('orientationchange');
          }
        }
      });
    },
    on: {
      init: function init(swiper) {
        var window = getWindow();

        if (swiper.params.resizeObserver && supportsResizeObserver()) {
          swiper.resize.createObserver();
          return;
        } // Emit resize


        window.addEventListener('resize', swiper.resize.resizeHandler); // Emit orientationchange

        window.addEventListener('orientationchange', swiper.resize.orientationChangeHandler);
      },
      destroy: function destroy(swiper) {
        var window = getWindow();
        swiper.resize.removeObserver();
        window.removeEventListener('resize', swiper.resize.resizeHandler);
        window.removeEventListener('orientationchange', swiper.resize.orientationChangeHandler);
      }
    }
  };
  var Observer = {
    attach: function attach(target, options) {
      if (options === void 0) {
        options = {};
      }

      var window = getWindow();
      var swiper = this;
      var ObserverFunc = window.MutationObserver || window.WebkitMutationObserver;
      var observer = new ObserverFunc(function (mutations) {
        // The observerUpdate event should only be triggered
        // once despite the number of mutations.  Additional
        // triggers are redundant and are very costly
        if (mutations.length === 1) {
          swiper.emit('observerUpdate', mutations[0]);
          return;
        }

        var observerUpdate = function observerUpdate() {
          swiper.emit('observerUpdate', mutations[0]);
        };

        if (window.requestAnimationFrame) {
          window.requestAnimationFrame(observerUpdate);
        } else {
          window.setTimeout(observerUpdate, 0);
        }
      });
      observer.observe(target, {
        attributes: typeof options.attributes === 'undefined' ? true : options.attributes,
        childList: typeof options.childList === 'undefined' ? true : options.childList,
        characterData: typeof options.characterData === 'undefined' ? true : options.characterData
      });
      swiper.observer.observers.push(observer);
    },
    init: function init() {
      var swiper = this;
      if (!swiper.support.observer || !swiper.params.observer) return;

      if (swiper.params.observeParents) {
        var containerParents = swiper.$el.parents();

        for (var i = 0; i < containerParents.length; i += 1) {
          swiper.observer.attach(containerParents[i]);
        }
      } // Observe container


      swiper.observer.attach(swiper.$el[0], {
        childList: swiper.params.observeSlideChildren
      }); // Observe wrapper

      swiper.observer.attach(swiper.$wrapperEl[0], {
        attributes: false
      });
    },
    destroy: function destroy() {
      var swiper = this;
      swiper.observer.observers.forEach(function (observer) {
        observer.disconnect();
      });
      swiper.observer.observers = [];
    }
  };
  var Observer$1 = {
    name: 'observer',
    params: {
      observer: false,
      observeParents: false,
      observeSlideChildren: false
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        observer: _extends({}, Observer, {
          observers: []
        })
      });
    },
    on: {
      init: function init(swiper) {
        swiper.observer.init();
      },
      destroy: function destroy(swiper) {
        swiper.observer.destroy();
      }
    }
  };
  var modular = {
    useParams: function useParams(instanceParams) {
      var instance = this;
      if (!instance.modules) return;
      Object.keys(instance.modules).forEach(function (moduleName) {
        var module = instance.modules[moduleName]; // Extend params

        if (module.params) {
          extend(instanceParams, module.params);
        }
      });
    },
    useModules: function useModules(modulesParams) {
      if (modulesParams === void 0) {
        modulesParams = {};
      }

      var instance = this;
      if (!instance.modules) return;
      Object.keys(instance.modules).forEach(function (moduleName) {
        var module = instance.modules[moduleName];
        var moduleParams = modulesParams[moduleName] || {}; // Add event listeners

        if (module.on && instance.on) {
          Object.keys(module.on).forEach(function (moduleEventName) {
            instance.on(moduleEventName, module.on[moduleEventName]);
          });
        } // Module create callback


        if (module.create) {
          module.create.bind(instance)(moduleParams);
        }
      });
    }
  };
  /* eslint-disable no-underscore-dangle */

  var eventsEmitter = {
    on: function on(events, handler, priority) {
      var self = this;
      if (typeof handler !== 'function') return self;
      var method = priority ? 'unshift' : 'push';
      events.split(' ').forEach(function (event) {
        if (!self.eventsListeners[event]) self.eventsListeners[event] = [];
        self.eventsListeners[event][method](handler);
      });
      return self;
    },
    once: function once(events, handler, priority) {
      var self = this;
      if (typeof handler !== 'function') return self;

      function onceHandler() {
        self.off(events, onceHandler);

        if (onceHandler.__emitterProxy) {
          delete onceHandler.__emitterProxy;
        }

        for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
          args[_key] = arguments[_key];
        }

        handler.apply(self, args);
      }

      onceHandler.__emitterProxy = handler;
      return self.on(events, onceHandler, priority);
    },
    onAny: function onAny(handler, priority) {
      var self = this;
      if (typeof handler !== 'function') return self;
      var method = priority ? 'unshift' : 'push';

      if (self.eventsAnyListeners.indexOf(handler) < 0) {
        self.eventsAnyListeners[method](handler);
      }

      return self;
    },
    offAny: function offAny(handler) {
      var self = this;
      if (!self.eventsAnyListeners) return self;
      var index = self.eventsAnyListeners.indexOf(handler);

      if (index >= 0) {
        self.eventsAnyListeners.splice(index, 1);
      }

      return self;
    },
    off: function off(events, handler) {
      var self = this;
      if (!self.eventsListeners) return self;
      events.split(' ').forEach(function (event) {
        if (typeof handler === 'undefined') {
          self.eventsListeners[event] = [];
        } else if (self.eventsListeners[event]) {
          self.eventsListeners[event].forEach(function (eventHandler, index) {
            if (eventHandler === handler || eventHandler.__emitterProxy && eventHandler.__emitterProxy === handler) {
              self.eventsListeners[event].splice(index, 1);
            }
          });
        }
      });
      return self;
    },
    emit: function emit() {
      var self = this;
      if (!self.eventsListeners) return self;
      var events;
      var data;
      var context;

      for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
        args[_key2] = arguments[_key2];
      }

      if (typeof args[0] === 'string' || Array.isArray(args[0])) {
        events = args[0];
        data = args.slice(1, args.length);
        context = self;
      } else {
        events = args[0].events;
        data = args[0].data;
        context = args[0].context || self;
      }

      data.unshift(context);
      var eventsArray = Array.isArray(events) ? events : events.split(' ');
      eventsArray.forEach(function (event) {
        if (self.eventsAnyListeners && self.eventsAnyListeners.length) {
          self.eventsAnyListeners.forEach(function (eventHandler) {
            eventHandler.apply(context, [event].concat(data));
          });
        }

        if (self.eventsListeners && self.eventsListeners[event]) {
          self.eventsListeners[event].forEach(function (eventHandler) {
            eventHandler.apply(context, data);
          });
        }
      });
      return self;
    }
  };

  function updateSize() {
    var swiper = this;
    var width;
    var height;
    var $el = swiper.$el;

    if (typeof swiper.params.width !== 'undefined' && swiper.params.width !== null) {
      width = swiper.params.width;
    } else {
      width = $el[0].clientWidth;
    }

    if (typeof swiper.params.height !== 'undefined' && swiper.params.height !== null) {
      height = swiper.params.height;
    } else {
      height = $el[0].clientHeight;
    }

    if (width === 0 && swiper.isHorizontal() || height === 0 && swiper.isVertical()) {
      return;
    } // Subtract paddings


    width = width - parseInt($el.css('padding-left') || 0, 10) - parseInt($el.css('padding-right') || 0, 10);
    height = height - parseInt($el.css('padding-top') || 0, 10) - parseInt($el.css('padding-bottom') || 0, 10);
    if (Number.isNaN(width)) width = 0;
    if (Number.isNaN(height)) height = 0;
    extend(swiper, {
      width: width,
      height: height,
      size: swiper.isHorizontal() ? width : height
    });
  }

  function updateSlides() {
    var swiper = this;

    function getDirectionLabel(property) {
      if (swiper.isHorizontal()) {
        return property;
      } // prettier-ignore


      return {
        'width': 'height',
        'margin-top': 'margin-left',
        'margin-bottom ': 'margin-right',
        'margin-left': 'margin-top',
        'margin-right': 'margin-bottom',
        'padding-left': 'padding-top',
        'padding-right': 'padding-bottom',
        'marginRight': 'marginBottom'
      }[property];
    }

    function getDirectionPropertyValue(node, label) {
      return parseFloat(node.getPropertyValue(getDirectionLabel(label)) || 0);
    }

    var params = swiper.params;
    var $wrapperEl = swiper.$wrapperEl,
        swiperSize = swiper.size,
        rtl = swiper.rtlTranslate,
        wrongRTL = swiper.wrongRTL;
    var isVirtual = swiper.virtual && params.virtual.enabled;
    var previousSlidesLength = isVirtual ? swiper.virtual.slides.length : swiper.slides.length;
    var slides = $wrapperEl.children("." + swiper.params.slideClass);
    var slidesLength = isVirtual ? swiper.virtual.slides.length : slides.length;
    var snapGrid = [];
    var slidesGrid = [];
    var slidesSizesGrid = [];
    var offsetBefore = params.slidesOffsetBefore;

    if (typeof offsetBefore === 'function') {
      offsetBefore = params.slidesOffsetBefore.call(swiper);
    }

    var offsetAfter = params.slidesOffsetAfter;

    if (typeof offsetAfter === 'function') {
      offsetAfter = params.slidesOffsetAfter.call(swiper);
    }

    var previousSnapGridLength = swiper.snapGrid.length;
    var previousSlidesGridLength = swiper.slidesGrid.length;
    var spaceBetween = params.spaceBetween;
    var slidePosition = -offsetBefore;
    var prevSlideSize = 0;
    var index = 0;

    if (typeof swiperSize === 'undefined') {
      return;
    }

    if (typeof spaceBetween === 'string' && spaceBetween.indexOf('%') >= 0) {
      spaceBetween = parseFloat(spaceBetween.replace('%', '')) / 100 * swiperSize;
    }

    swiper.virtualSize = -spaceBetween; // reset margins

    if (rtl) slides.css({
      marginLeft: '',
      marginBottom: '',
      marginTop: ''
    });else slides.css({
      marginRight: '',
      marginBottom: '',
      marginTop: ''
    });
    var slidesNumberEvenToRows;

    if (params.slidesPerColumn > 1) {
      if (Math.floor(slidesLength / params.slidesPerColumn) === slidesLength / swiper.params.slidesPerColumn) {
        slidesNumberEvenToRows = slidesLength;
      } else {
        slidesNumberEvenToRows = Math.ceil(slidesLength / params.slidesPerColumn) * params.slidesPerColumn;
      }

      if (params.slidesPerView !== 'auto' && params.slidesPerColumnFill === 'row') {
        slidesNumberEvenToRows = Math.max(slidesNumberEvenToRows, params.slidesPerView * params.slidesPerColumn);
      }
    } // Calc slides


    var slideSize;
    var slidesPerColumn = params.slidesPerColumn;
    var slidesPerRow = slidesNumberEvenToRows / slidesPerColumn;
    var numFullColumns = Math.floor(slidesLength / params.slidesPerColumn);

    for (var i = 0; i < slidesLength; i += 1) {
      slideSize = 0;
      var slide = slides.eq(i);

      if (params.slidesPerColumn > 1) {
        // Set slides order
        var newSlideOrderIndex = void 0;
        var column = void 0;
        var row = void 0;

        if (params.slidesPerColumnFill === 'row' && params.slidesPerGroup > 1) {
          var groupIndex = Math.floor(i / (params.slidesPerGroup * params.slidesPerColumn));
          var slideIndexInGroup = i - params.slidesPerColumn * params.slidesPerGroup * groupIndex;
          var columnsInGroup = groupIndex === 0 ? params.slidesPerGroup : Math.min(Math.ceil((slidesLength - groupIndex * slidesPerColumn * params.slidesPerGroup) / slidesPerColumn), params.slidesPerGroup);
          row = Math.floor(slideIndexInGroup / columnsInGroup);
          column = slideIndexInGroup - row * columnsInGroup + groupIndex * params.slidesPerGroup;
          newSlideOrderIndex = column + row * slidesNumberEvenToRows / slidesPerColumn;
          slide.css({
            '-webkit-box-ordinal-group': newSlideOrderIndex,
            '-moz-box-ordinal-group': newSlideOrderIndex,
            '-ms-flex-order': newSlideOrderIndex,
            '-webkit-order': newSlideOrderIndex,
            order: newSlideOrderIndex
          });
        } else if (params.slidesPerColumnFill === 'column') {
          column = Math.floor(i / slidesPerColumn);
          row = i - column * slidesPerColumn;

          if (column > numFullColumns || column === numFullColumns && row === slidesPerColumn - 1) {
            row += 1;

            if (row >= slidesPerColumn) {
              row = 0;
              column += 1;
            }
          }
        } else {
          row = Math.floor(i / slidesPerRow);
          column = i - row * slidesPerRow;
        }

        slide.css(getDirectionLabel('margin-top'), row !== 0 ? params.spaceBetween && params.spaceBetween + "px" : '');
      }

      if (slide.css('display') === 'none') continue; // eslint-disable-line

      if (params.slidesPerView === 'auto') {
        var slideStyles = getComputedStyle(slide[0]);
        var currentTransform = slide[0].style.transform;
        var currentWebKitTransform = slide[0].style.webkitTransform;

        if (currentTransform) {
          slide[0].style.transform = 'none';
        }

        if (currentWebKitTransform) {
          slide[0].style.webkitTransform = 'none';
        }

        if (params.roundLengths) {
          slideSize = swiper.isHorizontal() ? slide.outerWidth(true) : slide.outerHeight(true);
        } else {
          // eslint-disable-next-line
          var width = getDirectionPropertyValue(slideStyles, 'width');
          var paddingLeft = getDirectionPropertyValue(slideStyles, 'padding-left');
          var paddingRight = getDirectionPropertyValue(slideStyles, 'padding-right');
          var marginLeft = getDirectionPropertyValue(slideStyles, 'margin-left');
          var marginRight = getDirectionPropertyValue(slideStyles, 'margin-right');
          var boxSizing = slideStyles.getPropertyValue('box-sizing');

          if (boxSizing && boxSizing === 'border-box') {
            slideSize = width + marginLeft + marginRight;
          } else {
            var _slide$ = slide[0],
                clientWidth = _slide$.clientWidth,
                offsetWidth = _slide$.offsetWidth;
            slideSize = width + paddingLeft + paddingRight + marginLeft + marginRight + (offsetWidth - clientWidth);
          }
        }

        if (currentTransform) {
          slide[0].style.transform = currentTransform;
        }

        if (currentWebKitTransform) {
          slide[0].style.webkitTransform = currentWebKitTransform;
        }

        if (params.roundLengths) slideSize = Math.floor(slideSize);
      } else {
        slideSize = (swiperSize - (params.slidesPerView - 1) * spaceBetween) / params.slidesPerView;
        if (params.roundLengths) slideSize = Math.floor(slideSize);

        if (slides[i]) {
          slides[i].style[getDirectionLabel('width')] = slideSize + "px";
        }
      }

      if (slides[i]) {
        slides[i].swiperSlideSize = slideSize;
      }

      slidesSizesGrid.push(slideSize);

      if (params.centeredSlides) {
        slidePosition = slidePosition + slideSize / 2 + prevSlideSize / 2 + spaceBetween;
        if (prevSlideSize === 0 && i !== 0) slidePosition = slidePosition - swiperSize / 2 - spaceBetween;
        if (i === 0) slidePosition = slidePosition - swiperSize / 2 - spaceBetween;
        if (Math.abs(slidePosition) < 1 / 1000) slidePosition = 0;
        if (params.roundLengths) slidePosition = Math.floor(slidePosition);
        if (index % params.slidesPerGroup === 0) snapGrid.push(slidePosition);
        slidesGrid.push(slidePosition);
      } else {
        if (params.roundLengths) slidePosition = Math.floor(slidePosition);
        if ((index - Math.min(swiper.params.slidesPerGroupSkip, index)) % swiper.params.slidesPerGroup === 0) snapGrid.push(slidePosition);
        slidesGrid.push(slidePosition);
        slidePosition = slidePosition + slideSize + spaceBetween;
      }

      swiper.virtualSize += slideSize + spaceBetween;
      prevSlideSize = slideSize;
      index += 1;
    }

    swiper.virtualSize = Math.max(swiper.virtualSize, swiperSize) + offsetAfter;
    var newSlidesGrid;

    if (rtl && wrongRTL && (params.effect === 'slide' || params.effect === 'coverflow')) {
      $wrapperEl.css({
        width: swiper.virtualSize + params.spaceBetween + "px"
      });
    }

    if (params.setWrapperSize) {
      var _$wrapperEl$css;

      $wrapperEl.css((_$wrapperEl$css = {}, _$wrapperEl$css[getDirectionLabel('width')] = swiper.virtualSize + params.spaceBetween + "px", _$wrapperEl$css));
    }

    if (params.slidesPerColumn > 1) {
      var _$wrapperEl$css2;

      swiper.virtualSize = (slideSize + params.spaceBetween) * slidesNumberEvenToRows;
      swiper.virtualSize = Math.ceil(swiper.virtualSize / params.slidesPerColumn) - params.spaceBetween;
      $wrapperEl.css((_$wrapperEl$css2 = {}, _$wrapperEl$css2[getDirectionLabel('width')] = swiper.virtualSize + params.spaceBetween + "px", _$wrapperEl$css2));

      if (params.centeredSlides) {
        newSlidesGrid = [];

        for (var _i = 0; _i < snapGrid.length; _i += 1) {
          var slidesGridItem = snapGrid[_i];
          if (params.roundLengths) slidesGridItem = Math.floor(slidesGridItem);
          if (snapGrid[_i] < swiper.virtualSize + snapGrid[0]) newSlidesGrid.push(slidesGridItem);
        }

        snapGrid = newSlidesGrid;
      }
    } // Remove last grid elements depending on width


    if (!params.centeredSlides) {
      newSlidesGrid = [];

      for (var _i2 = 0; _i2 < snapGrid.length; _i2 += 1) {
        var _slidesGridItem = snapGrid[_i2];
        if (params.roundLengths) _slidesGridItem = Math.floor(_slidesGridItem);

        if (snapGrid[_i2] <= swiper.virtualSize - swiperSize) {
          newSlidesGrid.push(_slidesGridItem);
        }
      }

      snapGrid = newSlidesGrid;

      if (Math.floor(swiper.virtualSize - swiperSize) - Math.floor(snapGrid[snapGrid.length - 1]) > 1) {
        snapGrid.push(swiper.virtualSize - swiperSize);
      }
    }

    if (snapGrid.length === 0) snapGrid = [0];

    if (params.spaceBetween !== 0) {
      var _slides$filter$css;

      var key = swiper.isHorizontal() && rtl ? 'marginLeft' : getDirectionLabel('marginRight');
      slides.filter(function (_, slideIndex) {
        if (!params.cssMode) return true;

        if (slideIndex === slides.length - 1) {
          return false;
        }

        return true;
      }).css((_slides$filter$css = {}, _slides$filter$css[key] = spaceBetween + "px", _slides$filter$css));
    }

    if (params.centeredSlides && params.centeredSlidesBounds) {
      var allSlidesSize = 0;
      slidesSizesGrid.forEach(function (slideSizeValue) {
        allSlidesSize += slideSizeValue + (params.spaceBetween ? params.spaceBetween : 0);
      });
      allSlidesSize -= params.spaceBetween;
      var maxSnap = allSlidesSize - swiperSize;
      snapGrid = snapGrid.map(function (snap) {
        if (snap < 0) return -offsetBefore;
        if (snap > maxSnap) return maxSnap + offsetAfter;
        return snap;
      });
    }

    if (params.centerInsufficientSlides) {
      var _allSlidesSize = 0;
      slidesSizesGrid.forEach(function (slideSizeValue) {
        _allSlidesSize += slideSizeValue + (params.spaceBetween ? params.spaceBetween : 0);
      });
      _allSlidesSize -= params.spaceBetween;

      if (_allSlidesSize < swiperSize) {
        var allSlidesOffset = (swiperSize - _allSlidesSize) / 2;
        snapGrid.forEach(function (snap, snapIndex) {
          snapGrid[snapIndex] = snap - allSlidesOffset;
        });
        slidesGrid.forEach(function (snap, snapIndex) {
          slidesGrid[snapIndex] = snap + allSlidesOffset;
        });
      }
    }

    extend(swiper, {
      slides: slides,
      snapGrid: snapGrid,
      slidesGrid: slidesGrid,
      slidesSizesGrid: slidesSizesGrid
    });

    if (slidesLength !== previousSlidesLength) {
      swiper.emit('slidesLengthChange');
    }

    if (snapGrid.length !== previousSnapGridLength) {
      if (swiper.params.watchOverflow) swiper.checkOverflow();
      swiper.emit('snapGridLengthChange');
    }

    if (slidesGrid.length !== previousSlidesGridLength) {
      swiper.emit('slidesGridLengthChange');
    }

    if (params.watchSlidesProgress || params.watchSlidesVisibility) {
      swiper.updateSlidesOffset();
    }
  }

  function updateAutoHeight(speed) {
    var swiper = this;
    var activeSlides = [];
    var isVirtual = swiper.virtual && swiper.params.virtual.enabled;
    var newHeight = 0;
    var i;

    if (typeof speed === 'number') {
      swiper.setTransition(speed);
    } else if (speed === true) {
      swiper.setTransition(swiper.params.speed);
    }

    var getSlideByIndex = function getSlideByIndex(index) {
      if (isVirtual) {
        return swiper.slides.filter(function (el) {
          return parseInt(el.getAttribute('data-swiper-slide-index'), 10) === index;
        })[0];
      }

      return swiper.slides.eq(index)[0];
    }; // Find slides currently in view


    if (swiper.params.slidesPerView !== 'auto' && swiper.params.slidesPerView > 1) {
      if (swiper.params.centeredSlides) {
        swiper.visibleSlides.each(function (slide) {
          activeSlides.push(slide);
        });
      } else {
        for (i = 0; i < Math.ceil(swiper.params.slidesPerView); i += 1) {
          var index = swiper.activeIndex + i;
          if (index > swiper.slides.length && !isVirtual) break;
          activeSlides.push(getSlideByIndex(index));
        }
      }
    } else {
      activeSlides.push(getSlideByIndex(swiper.activeIndex));
    } // Find new height from highest slide in view


    for (i = 0; i < activeSlides.length; i += 1) {
      if (typeof activeSlides[i] !== 'undefined') {
        var height = activeSlides[i].offsetHeight;
        newHeight = height > newHeight ? height : newHeight;
      }
    } // Update Height


    if (newHeight) swiper.$wrapperEl.css('height', newHeight + "px");
  }

  function updateSlidesOffset() {
    var swiper = this;
    var slides = swiper.slides;

    for (var i = 0; i < slides.length; i += 1) {
      slides[i].swiperSlideOffset = swiper.isHorizontal() ? slides[i].offsetLeft : slides[i].offsetTop;
    }
  }

  function updateSlidesProgress(translate) {
    if (translate === void 0) {
      translate = this && this.translate || 0;
    }

    var swiper = this;
    var params = swiper.params;
    var slides = swiper.slides,
        rtl = swiper.rtlTranslate;
    if (slides.length === 0) return;
    if (typeof slides[0].swiperSlideOffset === 'undefined') swiper.updateSlidesOffset();
    var offsetCenter = -translate;
    if (rtl) offsetCenter = translate; // Visible Slides

    slides.removeClass(params.slideVisibleClass);
    swiper.visibleSlidesIndexes = [];
    swiper.visibleSlides = [];

    for (var i = 0; i < slides.length; i += 1) {
      var slide = slides[i];
      var slideProgress = (offsetCenter + (params.centeredSlides ? swiper.minTranslate() : 0) - slide.swiperSlideOffset) / (slide.swiperSlideSize + params.spaceBetween);

      if (params.watchSlidesVisibility || params.centeredSlides && params.autoHeight) {
        var slideBefore = -(offsetCenter - slide.swiperSlideOffset);
        var slideAfter = slideBefore + swiper.slidesSizesGrid[i];
        var isVisible = slideBefore >= 0 && slideBefore < swiper.size - 1 || slideAfter > 1 && slideAfter <= swiper.size || slideBefore <= 0 && slideAfter >= swiper.size;

        if (isVisible) {
          swiper.visibleSlides.push(slide);
          swiper.visibleSlidesIndexes.push(i);
          slides.eq(i).addClass(params.slideVisibleClass);
        }
      }

      slide.progress = rtl ? -slideProgress : slideProgress;
    }

    swiper.visibleSlides = $(swiper.visibleSlides);
  }

  function updateProgress(translate) {
    var swiper = this;

    if (typeof translate === 'undefined') {
      var multiplier = swiper.rtlTranslate ? -1 : 1; // eslint-disable-next-line

      translate = swiper && swiper.translate && swiper.translate * multiplier || 0;
    }

    var params = swiper.params;
    var translatesDiff = swiper.maxTranslate() - swiper.minTranslate();
    var progress = swiper.progress,
        isBeginning = swiper.isBeginning,
        isEnd = swiper.isEnd;
    var wasBeginning = isBeginning;
    var wasEnd = isEnd;

    if (translatesDiff === 0) {
      progress = 0;
      isBeginning = true;
      isEnd = true;
    } else {
      progress = (translate - swiper.minTranslate()) / translatesDiff;
      isBeginning = progress <= 0;
      isEnd = progress >= 1;
    }

    extend(swiper, {
      progress: progress,
      isBeginning: isBeginning,
      isEnd: isEnd
    });
    if (params.watchSlidesProgress || params.watchSlidesVisibility || params.centeredSlides && params.autoHeight) swiper.updateSlidesProgress(translate);

    if (isBeginning && !wasBeginning) {
      swiper.emit('reachBeginning toEdge');
    }

    if (isEnd && !wasEnd) {
      swiper.emit('reachEnd toEdge');
    }

    if (wasBeginning && !isBeginning || wasEnd && !isEnd) {
      swiper.emit('fromEdge');
    }

    swiper.emit('progress', progress);
  }

  function updateSlidesClasses() {
    var swiper = this;
    var slides = swiper.slides,
        params = swiper.params,
        $wrapperEl = swiper.$wrapperEl,
        activeIndex = swiper.activeIndex,
        realIndex = swiper.realIndex;
    var isVirtual = swiper.virtual && params.virtual.enabled;
    slides.removeClass(params.slideActiveClass + " " + params.slideNextClass + " " + params.slidePrevClass + " " + params.slideDuplicateActiveClass + " " + params.slideDuplicateNextClass + " " + params.slideDuplicatePrevClass);
    var activeSlide;

    if (isVirtual) {
      activeSlide = swiper.$wrapperEl.find("." + params.slideClass + "[data-swiper-slide-index=\"" + activeIndex + "\"]");
    } else {
      activeSlide = slides.eq(activeIndex);
    } // Active classes


    activeSlide.addClass(params.slideActiveClass);

    if (params.loop) {
      // Duplicate to all looped slides
      if (activeSlide.hasClass(params.slideDuplicateClass)) {
        $wrapperEl.children("." + params.slideClass + ":not(." + params.slideDuplicateClass + ")[data-swiper-slide-index=\"" + realIndex + "\"]").addClass(params.slideDuplicateActiveClass);
      } else {
        $wrapperEl.children("." + params.slideClass + "." + params.slideDuplicateClass + "[data-swiper-slide-index=\"" + realIndex + "\"]").addClass(params.slideDuplicateActiveClass);
      }
    } // Next Slide


    var nextSlide = activeSlide.nextAll("." + params.slideClass).eq(0).addClass(params.slideNextClass);

    if (params.loop && nextSlide.length === 0) {
      nextSlide = slides.eq(0);
      nextSlide.addClass(params.slideNextClass);
    } // Prev Slide


    var prevSlide = activeSlide.prevAll("." + params.slideClass).eq(0).addClass(params.slidePrevClass);

    if (params.loop && prevSlide.length === 0) {
      prevSlide = slides.eq(-1);
      prevSlide.addClass(params.slidePrevClass);
    }

    if (params.loop) {
      // Duplicate to all looped slides
      if (nextSlide.hasClass(params.slideDuplicateClass)) {
        $wrapperEl.children("." + params.slideClass + ":not(." + params.slideDuplicateClass + ")[data-swiper-slide-index=\"" + nextSlide.attr('data-swiper-slide-index') + "\"]").addClass(params.slideDuplicateNextClass);
      } else {
        $wrapperEl.children("." + params.slideClass + "." + params.slideDuplicateClass + "[data-swiper-slide-index=\"" + nextSlide.attr('data-swiper-slide-index') + "\"]").addClass(params.slideDuplicateNextClass);
      }

      if (prevSlide.hasClass(params.slideDuplicateClass)) {
        $wrapperEl.children("." + params.slideClass + ":not(." + params.slideDuplicateClass + ")[data-swiper-slide-index=\"" + prevSlide.attr('data-swiper-slide-index') + "\"]").addClass(params.slideDuplicatePrevClass);
      } else {
        $wrapperEl.children("." + params.slideClass + "." + params.slideDuplicateClass + "[data-swiper-slide-index=\"" + prevSlide.attr('data-swiper-slide-index') + "\"]").addClass(params.slideDuplicatePrevClass);
      }
    }

    swiper.emitSlidesClasses();
  }

  function updateActiveIndex(newActiveIndex) {
    var swiper = this;
    var translate = swiper.rtlTranslate ? swiper.translate : -swiper.translate;
    var slidesGrid = swiper.slidesGrid,
        snapGrid = swiper.snapGrid,
        params = swiper.params,
        previousIndex = swiper.activeIndex,
        previousRealIndex = swiper.realIndex,
        previousSnapIndex = swiper.snapIndex;
    var activeIndex = newActiveIndex;
    var snapIndex;

    if (typeof activeIndex === 'undefined') {
      for (var i = 0; i < slidesGrid.length; i += 1) {
        if (typeof slidesGrid[i + 1] !== 'undefined') {
          if (translate >= slidesGrid[i] && translate < slidesGrid[i + 1] - (slidesGrid[i + 1] - slidesGrid[i]) / 2) {
            activeIndex = i;
          } else if (translate >= slidesGrid[i] && translate < slidesGrid[i + 1]) {
            activeIndex = i + 1;
          }
        } else if (translate >= slidesGrid[i]) {
          activeIndex = i;
        }
      } // Normalize slideIndex


      if (params.normalizeSlideIndex) {
        if (activeIndex < 0 || typeof activeIndex === 'undefined') activeIndex = 0;
      }
    }

    if (snapGrid.indexOf(translate) >= 0) {
      snapIndex = snapGrid.indexOf(translate);
    } else {
      var skip = Math.min(params.slidesPerGroupSkip, activeIndex);
      snapIndex = skip + Math.floor((activeIndex - skip) / params.slidesPerGroup);
    }

    if (snapIndex >= snapGrid.length) snapIndex = snapGrid.length - 1;

    if (activeIndex === previousIndex) {
      if (snapIndex !== previousSnapIndex) {
        swiper.snapIndex = snapIndex;
        swiper.emit('snapIndexChange');
      }

      return;
    } // Get real index


    var realIndex = parseInt(swiper.slides.eq(activeIndex).attr('data-swiper-slide-index') || activeIndex, 10);
    extend(swiper, {
      snapIndex: snapIndex,
      realIndex: realIndex,
      previousIndex: previousIndex,
      activeIndex: activeIndex
    });
    swiper.emit('activeIndexChange');
    swiper.emit('snapIndexChange');

    if (previousRealIndex !== realIndex) {
      swiper.emit('realIndexChange');
    }

    if (swiper.initialized || swiper.params.runCallbacksOnInit) {
      swiper.emit('slideChange');
    }
  }

  function updateClickedSlide(e) {
    var swiper = this;
    var params = swiper.params;
    var slide = $(e.target).closest("." + params.slideClass)[0];
    var slideFound = false;
    var slideIndex;

    if (slide) {
      for (var i = 0; i < swiper.slides.length; i += 1) {
        if (swiper.slides[i] === slide) {
          slideFound = true;
          slideIndex = i;
          break;
        }
      }
    }

    if (slide && slideFound) {
      swiper.clickedSlide = slide;

      if (swiper.virtual && swiper.params.virtual.enabled) {
        swiper.clickedIndex = parseInt($(slide).attr('data-swiper-slide-index'), 10);
      } else {
        swiper.clickedIndex = slideIndex;
      }
    } else {
      swiper.clickedSlide = undefined;
      swiper.clickedIndex = undefined;
      return;
    }

    if (params.slideToClickedSlide && swiper.clickedIndex !== undefined && swiper.clickedIndex !== swiper.activeIndex) {
      swiper.slideToClickedSlide();
    }
  }

  var update = {
    updateSize: updateSize,
    updateSlides: updateSlides,
    updateAutoHeight: updateAutoHeight,
    updateSlidesOffset: updateSlidesOffset,
    updateSlidesProgress: updateSlidesProgress,
    updateProgress: updateProgress,
    updateSlidesClasses: updateSlidesClasses,
    updateActiveIndex: updateActiveIndex,
    updateClickedSlide: updateClickedSlide
  };

  function getSwiperTranslate(axis) {
    if (axis === void 0) {
      axis = this.isHorizontal() ? 'x' : 'y';
    }

    var swiper = this;
    var params = swiper.params,
        rtl = swiper.rtlTranslate,
        translate = swiper.translate,
        $wrapperEl = swiper.$wrapperEl;

    if (params.virtualTranslate) {
      return rtl ? -translate : translate;
    }

    if (params.cssMode) {
      return translate;
    }

    var currentTranslate = getTranslate($wrapperEl[0], axis);
    if (rtl) currentTranslate = -currentTranslate;
    return currentTranslate || 0;
  }

  function setTranslate(translate, byController) {
    var swiper = this;
    var rtl = swiper.rtlTranslate,
        params = swiper.params,
        $wrapperEl = swiper.$wrapperEl,
        wrapperEl = swiper.wrapperEl,
        progress = swiper.progress;
    var x = 0;
    var y = 0;
    var z = 0;

    if (swiper.isHorizontal()) {
      x = rtl ? -translate : translate;
    } else {
      y = translate;
    }

    if (params.roundLengths) {
      x = Math.floor(x);
      y = Math.floor(y);
    }

    if (params.cssMode) {
      wrapperEl[swiper.isHorizontal() ? 'scrollLeft' : 'scrollTop'] = swiper.isHorizontal() ? -x : -y;
    } else if (!params.virtualTranslate) {
      $wrapperEl.transform("translate3d(" + x + "px, " + y + "px, " + z + "px)");
    }

    swiper.previousTranslate = swiper.translate;
    swiper.translate = swiper.isHorizontal() ? x : y; // Check if we need to update progress

    var newProgress;
    var translatesDiff = swiper.maxTranslate() - swiper.minTranslate();

    if (translatesDiff === 0) {
      newProgress = 0;
    } else {
      newProgress = (translate - swiper.minTranslate()) / translatesDiff;
    }

    if (newProgress !== progress) {
      swiper.updateProgress(translate);
    }

    swiper.emit('setTranslate', swiper.translate, byController);
  }

  function minTranslate() {
    return -this.snapGrid[0];
  }

  function maxTranslate() {
    return -this.snapGrid[this.snapGrid.length - 1];
  }

  function translateTo(translate, speed, runCallbacks, translateBounds, internal) {
    if (translate === void 0) {
      translate = 0;
    }

    if (speed === void 0) {
      speed = this.params.speed;
    }

    if (runCallbacks === void 0) {
      runCallbacks = true;
    }

    if (translateBounds === void 0) {
      translateBounds = true;
    }

    var swiper = this;
    var params = swiper.params,
        wrapperEl = swiper.wrapperEl;

    if (swiper.animating && params.preventInteractionOnTransition) {
      return false;
    }

    var minTranslate = swiper.minTranslate();
    var maxTranslate = swiper.maxTranslate();
    var newTranslate;
    if (translateBounds && translate > minTranslate) newTranslate = minTranslate;else if (translateBounds && translate < maxTranslate) newTranslate = maxTranslate;else newTranslate = translate; // Update progress

    swiper.updateProgress(newTranslate);

    if (params.cssMode) {
      var isH = swiper.isHorizontal();

      if (speed === 0) {
        wrapperEl[isH ? 'scrollLeft' : 'scrollTop'] = -newTranslate;
      } else {
        // eslint-disable-next-line
        if (wrapperEl.scrollTo) {
          var _wrapperEl$scrollTo;

          wrapperEl.scrollTo((_wrapperEl$scrollTo = {}, _wrapperEl$scrollTo[isH ? 'left' : 'top'] = -newTranslate, _wrapperEl$scrollTo.behavior = 'smooth', _wrapperEl$scrollTo));
        } else {
          wrapperEl[isH ? 'scrollLeft' : 'scrollTop'] = -newTranslate;
        }
      }

      return true;
    }

    if (speed === 0) {
      swiper.setTransition(0);
      swiper.setTranslate(newTranslate);

      if (runCallbacks) {
        swiper.emit('beforeTransitionStart', speed, internal);
        swiper.emit('transitionEnd');
      }
    } else {
      swiper.setTransition(speed);
      swiper.setTranslate(newTranslate);

      if (runCallbacks) {
        swiper.emit('beforeTransitionStart', speed, internal);
        swiper.emit('transitionStart');
      }

      if (!swiper.animating) {
        swiper.animating = true;

        if (!swiper.onTranslateToWrapperTransitionEnd) {
          swiper.onTranslateToWrapperTransitionEnd = function transitionEnd(e) {
            if (!swiper || swiper.destroyed) return;
            if (e.target !== this) return;
            swiper.$wrapperEl[0].removeEventListener('transitionend', swiper.onTranslateToWrapperTransitionEnd);
            swiper.$wrapperEl[0].removeEventListener('webkitTransitionEnd', swiper.onTranslateToWrapperTransitionEnd);
            swiper.onTranslateToWrapperTransitionEnd = null;
            delete swiper.onTranslateToWrapperTransitionEnd;

            if (runCallbacks) {
              swiper.emit('transitionEnd');
            }
          };
        }

        swiper.$wrapperEl[0].addEventListener('transitionend', swiper.onTranslateToWrapperTransitionEnd);
        swiper.$wrapperEl[0].addEventListener('webkitTransitionEnd', swiper.onTranslateToWrapperTransitionEnd);
      }
    }

    return true;
  }

  var translate = {
    getTranslate: getSwiperTranslate,
    setTranslate: setTranslate,
    minTranslate: minTranslate,
    maxTranslate: maxTranslate,
    translateTo: translateTo
  };

  function setTransition(duration, byController) {
    var swiper = this;

    if (!swiper.params.cssMode) {
      swiper.$wrapperEl.transition(duration);
    }

    swiper.emit('setTransition', duration, byController);
  }

  function transitionStart(runCallbacks, direction) {
    if (runCallbacks === void 0) {
      runCallbacks = true;
    }

    var swiper = this;
    var activeIndex = swiper.activeIndex,
        params = swiper.params,
        previousIndex = swiper.previousIndex;
    if (params.cssMode) return;

    if (params.autoHeight) {
      swiper.updateAutoHeight();
    }

    var dir = direction;

    if (!dir) {
      if (activeIndex > previousIndex) dir = 'next';else if (activeIndex < previousIndex) dir = 'prev';else dir = 'reset';
    }

    swiper.emit('transitionStart');

    if (runCallbacks && activeIndex !== previousIndex) {
      if (dir === 'reset') {
        swiper.emit('slideResetTransitionStart');
        return;
      }

      swiper.emit('slideChangeTransitionStart');

      if (dir === 'next') {
        swiper.emit('slideNextTransitionStart');
      } else {
        swiper.emit('slidePrevTransitionStart');
      }
    }
  }

  function transitionEnd(runCallbacks, direction) {
    if (runCallbacks === void 0) {
      runCallbacks = true;
    }

    var swiper = this;
    var activeIndex = swiper.activeIndex,
        previousIndex = swiper.previousIndex,
        params = swiper.params;
    swiper.animating = false;
    if (params.cssMode) return;
    swiper.setTransition(0);
    var dir = direction;

    if (!dir) {
      if (activeIndex > previousIndex) dir = 'next';else if (activeIndex < previousIndex) dir = 'prev';else dir = 'reset';
    }

    swiper.emit('transitionEnd');

    if (runCallbacks && activeIndex !== previousIndex) {
      if (dir === 'reset') {
        swiper.emit('slideResetTransitionEnd');
        return;
      }

      swiper.emit('slideChangeTransitionEnd');

      if (dir === 'next') {
        swiper.emit('slideNextTransitionEnd');
      } else {
        swiper.emit('slidePrevTransitionEnd');
      }
    }
  }

  var transition = {
    setTransition: setTransition,
    transitionStart: transitionStart,
    transitionEnd: transitionEnd
  };

  function slideTo(index, speed, runCallbacks, internal, initial) {
    if (index === void 0) {
      index = 0;
    }

    if (speed === void 0) {
      speed = this.params.speed;
    }

    if (runCallbacks === void 0) {
      runCallbacks = true;
    }

    if (typeof index !== 'number' && typeof index !== 'string') {
      throw new Error("The 'index' argument cannot have type other than 'number' or 'string'. [" + _typeof(index) + "] given.");
    }

    if (typeof index === 'string') {
      /**
       * The `index` argument converted from `string` to `number`.
       * @type {number}
       */
      var indexAsNumber = parseInt(index, 10);
      /**
       * Determines whether the `index` argument is a valid `number`
       * after being converted from the `string` type.
       * @type {boolean}
       */

      var isValidNumber = isFinite(indexAsNumber);

      if (!isValidNumber) {
        throw new Error("The passed-in 'index' (string) couldn't be converted to 'number'. [" + index + "] given.");
      } // Knowing that the converted `index` is a valid number,
      // we can update the original argument's value.


      index = indexAsNumber;
    }

    var swiper = this;
    var slideIndex = index;
    if (slideIndex < 0) slideIndex = 0;
    var params = swiper.params,
        snapGrid = swiper.snapGrid,
        slidesGrid = swiper.slidesGrid,
        previousIndex = swiper.previousIndex,
        activeIndex = swiper.activeIndex,
        rtl = swiper.rtlTranslate,
        wrapperEl = swiper.wrapperEl,
        enabled = swiper.enabled;

    if (swiper.animating && params.preventInteractionOnTransition || !enabled && !internal && !initial) {
      return false;
    }

    var skip = Math.min(swiper.params.slidesPerGroupSkip, slideIndex);
    var snapIndex = skip + Math.floor((slideIndex - skip) / swiper.params.slidesPerGroup);
    if (snapIndex >= snapGrid.length) snapIndex = snapGrid.length - 1;

    if ((activeIndex || params.initialSlide || 0) === (previousIndex || 0) && runCallbacks) {
      swiper.emit('beforeSlideChangeStart');
    }

    var translate = -snapGrid[snapIndex]; // Update progress

    swiper.updateProgress(translate); // Normalize slideIndex

    if (params.normalizeSlideIndex) {
      for (var i = 0; i < slidesGrid.length; i += 1) {
        var normalizedTranslate = -Math.floor(translate * 100);
        var normalizedGird = Math.floor(slidesGrid[i] * 100);
        var normalizedGridNext = Math.floor(slidesGrid[i + 1] * 100);

        if (typeof slidesGrid[i + 1] !== 'undefined') {
          if (normalizedTranslate >= normalizedGird && normalizedTranslate < normalizedGridNext - (normalizedGridNext - normalizedGird) / 2) {
            slideIndex = i;
          } else if (normalizedTranslate >= normalizedGird && normalizedTranslate < normalizedGridNext) {
            slideIndex = i + 1;
          }
        } else if (normalizedTranslate >= normalizedGird) {
          slideIndex = i;
        }
      }
    } // Directions locks


    if (swiper.initialized && slideIndex !== activeIndex) {
      if (!swiper.allowSlideNext && translate < swiper.translate && translate < swiper.minTranslate()) {
        return false;
      }

      if (!swiper.allowSlidePrev && translate > swiper.translate && translate > swiper.maxTranslate()) {
        if ((activeIndex || 0) !== slideIndex) return false;
      }
    }

    var direction;
    if (slideIndex > activeIndex) direction = 'next';else if (slideIndex < activeIndex) direction = 'prev';else direction = 'reset'; // Update Index

    if (rtl && -translate === swiper.translate || !rtl && translate === swiper.translate) {
      swiper.updateActiveIndex(slideIndex); // Update Height

      if (params.autoHeight) {
        swiper.updateAutoHeight();
      }

      swiper.updateSlidesClasses();

      if (params.effect !== 'slide') {
        swiper.setTranslate(translate);
      }

      if (direction !== 'reset') {
        swiper.transitionStart(runCallbacks, direction);
        swiper.transitionEnd(runCallbacks, direction);
      }

      return false;
    }

    if (params.cssMode) {
      var isH = swiper.isHorizontal();
      var t = -translate;

      if (rtl) {
        t = wrapperEl.scrollWidth - wrapperEl.offsetWidth - t;
      }

      if (speed === 0) {
        wrapperEl[isH ? 'scrollLeft' : 'scrollTop'] = t;
      } else {
        // eslint-disable-next-line
        if (wrapperEl.scrollTo) {
          var _wrapperEl$scrollTo;

          wrapperEl.scrollTo((_wrapperEl$scrollTo = {}, _wrapperEl$scrollTo[isH ? 'left' : 'top'] = t, _wrapperEl$scrollTo.behavior = 'smooth', _wrapperEl$scrollTo));
        } else {
          wrapperEl[isH ? 'scrollLeft' : 'scrollTop'] = t;
        }
      }

      return true;
    }

    if (speed === 0) {
      swiper.setTransition(0);
      swiper.setTranslate(translate);
      swiper.updateActiveIndex(slideIndex);
      swiper.updateSlidesClasses();
      swiper.emit('beforeTransitionStart', speed, internal);
      swiper.transitionStart(runCallbacks, direction);
      swiper.transitionEnd(runCallbacks, direction);
    } else {
      swiper.setTransition(speed);
      swiper.setTranslate(translate);
      swiper.updateActiveIndex(slideIndex);
      swiper.updateSlidesClasses();
      swiper.emit('beforeTransitionStart', speed, internal);
      swiper.transitionStart(runCallbacks, direction);

      if (!swiper.animating) {
        swiper.animating = true;

        if (!swiper.onSlideToWrapperTransitionEnd) {
          swiper.onSlideToWrapperTransitionEnd = function transitionEnd(e) {
            if (!swiper || swiper.destroyed) return;
            if (e.target !== this) return;
            swiper.$wrapperEl[0].removeEventListener('transitionend', swiper.onSlideToWrapperTransitionEnd);
            swiper.$wrapperEl[0].removeEventListener('webkitTransitionEnd', swiper.onSlideToWrapperTransitionEnd);
            swiper.onSlideToWrapperTransitionEnd = null;
            delete swiper.onSlideToWrapperTransitionEnd;
            swiper.transitionEnd(runCallbacks, direction);
          };
        }

        swiper.$wrapperEl[0].addEventListener('transitionend', swiper.onSlideToWrapperTransitionEnd);
        swiper.$wrapperEl[0].addEventListener('webkitTransitionEnd', swiper.onSlideToWrapperTransitionEnd);
      }
    }

    return true;
  }

  function slideToLoop(index, speed, runCallbacks, internal) {
    if (index === void 0) {
      index = 0;
    }

    if (speed === void 0) {
      speed = this.params.speed;
    }

    if (runCallbacks === void 0) {
      runCallbacks = true;
    }

    var swiper = this;
    var newIndex = index;

    if (swiper.params.loop) {
      newIndex += swiper.loopedSlides;
    }

    return swiper.slideTo(newIndex, speed, runCallbacks, internal);
  }
  /* eslint no-unused-vars: "off" */


  function slideNext(speed, runCallbacks, internal) {
    if (speed === void 0) {
      speed = this.params.speed;
    }

    if (runCallbacks === void 0) {
      runCallbacks = true;
    }

    var swiper = this;
    var params = swiper.params,
        animating = swiper.animating,
        enabled = swiper.enabled;
    if (!enabled) return swiper;
    var increment = swiper.activeIndex < params.slidesPerGroupSkip ? 1 : params.slidesPerGroup;

    if (params.loop) {
      if (animating && params.loopPreventsSlide) return false;
      swiper.loopFix(); // eslint-disable-next-line

      swiper._clientLeft = swiper.$wrapperEl[0].clientLeft;
    }

    return swiper.slideTo(swiper.activeIndex + increment, speed, runCallbacks, internal);
  }
  /* eslint no-unused-vars: "off" */


  function slidePrev(speed, runCallbacks, internal) {
    if (speed === void 0) {
      speed = this.params.speed;
    }

    if (runCallbacks === void 0) {
      runCallbacks = true;
    }

    var swiper = this;
    var params = swiper.params,
        animating = swiper.animating,
        snapGrid = swiper.snapGrid,
        slidesGrid = swiper.slidesGrid,
        rtlTranslate = swiper.rtlTranslate,
        enabled = swiper.enabled;
    if (!enabled) return swiper;

    if (params.loop) {
      if (animating && params.loopPreventsSlide) return false;
      swiper.loopFix(); // eslint-disable-next-line

      swiper._clientLeft = swiper.$wrapperEl[0].clientLeft;
    }

    var translate = rtlTranslate ? swiper.translate : -swiper.translate;

    function normalize(val) {
      if (val < 0) return -Math.floor(Math.abs(val));
      return Math.floor(val);
    }

    var normalizedTranslate = normalize(translate);
    var normalizedSnapGrid = snapGrid.map(function (val) {
      return normalize(val);
    });
    var prevSnap = snapGrid[normalizedSnapGrid.indexOf(normalizedTranslate) - 1];

    if (typeof prevSnap === 'undefined' && params.cssMode) {
      snapGrid.forEach(function (snap) {
        if (!prevSnap && normalizedTranslate >= snap) prevSnap = snap;
      });
    }

    var prevIndex;

    if (typeof prevSnap !== 'undefined') {
      prevIndex = slidesGrid.indexOf(prevSnap);
      if (prevIndex < 0) prevIndex = swiper.activeIndex - 1;
    }

    return swiper.slideTo(prevIndex, speed, runCallbacks, internal);
  }
  /* eslint no-unused-vars: "off" */


  function slideReset(speed, runCallbacks, internal) {
    if (speed === void 0) {
      speed = this.params.speed;
    }

    if (runCallbacks === void 0) {
      runCallbacks = true;
    }

    var swiper = this;
    return swiper.slideTo(swiper.activeIndex, speed, runCallbacks, internal);
  }
  /* eslint no-unused-vars: "off" */


  function slideToClosest(speed, runCallbacks, internal, threshold) {
    if (speed === void 0) {
      speed = this.params.speed;
    }

    if (runCallbacks === void 0) {
      runCallbacks = true;
    }

    if (threshold === void 0) {
      threshold = 0.5;
    }

    var swiper = this;
    var index = swiper.activeIndex;
    var skip = Math.min(swiper.params.slidesPerGroupSkip, index);
    var snapIndex = skip + Math.floor((index - skip) / swiper.params.slidesPerGroup);
    var translate = swiper.rtlTranslate ? swiper.translate : -swiper.translate;

    if (translate >= swiper.snapGrid[snapIndex]) {
      // The current translate is on or after the current snap index, so the choice
      // is between the current index and the one after it.
      var currentSnap = swiper.snapGrid[snapIndex];
      var nextSnap = swiper.snapGrid[snapIndex + 1];

      if (translate - currentSnap > (nextSnap - currentSnap) * threshold) {
        index += swiper.params.slidesPerGroup;
      }
    } else {
      // The current translate is before the current snap index, so the choice
      // is between the current index and the one before it.
      var prevSnap = swiper.snapGrid[snapIndex - 1];
      var _currentSnap = swiper.snapGrid[snapIndex];

      if (translate - prevSnap <= (_currentSnap - prevSnap) * threshold) {
        index -= swiper.params.slidesPerGroup;
      }
    }

    index = Math.max(index, 0);
    index = Math.min(index, swiper.slidesGrid.length - 1);
    return swiper.slideTo(index, speed, runCallbacks, internal);
  }

  function slideToClickedSlide() {
    var swiper = this;
    var params = swiper.params,
        $wrapperEl = swiper.$wrapperEl;
    var slidesPerView = params.slidesPerView === 'auto' ? swiper.slidesPerViewDynamic() : params.slidesPerView;
    var slideToIndex = swiper.clickedIndex;
    var realIndex;

    if (params.loop) {
      if (swiper.animating) return;
      realIndex = parseInt($(swiper.clickedSlide).attr('data-swiper-slide-index'), 10);

      if (params.centeredSlides) {
        if (slideToIndex < swiper.loopedSlides - slidesPerView / 2 || slideToIndex > swiper.slides.length - swiper.loopedSlides + slidesPerView / 2) {
          swiper.loopFix();
          slideToIndex = $wrapperEl.children("." + params.slideClass + "[data-swiper-slide-index=\"" + realIndex + "\"]:not(." + params.slideDuplicateClass + ")").eq(0).index();
          nextTick(function () {
            swiper.slideTo(slideToIndex);
          });
        } else {
          swiper.slideTo(slideToIndex);
        }
      } else if (slideToIndex > swiper.slides.length - slidesPerView) {
        swiper.loopFix();
        slideToIndex = $wrapperEl.children("." + params.slideClass + "[data-swiper-slide-index=\"" + realIndex + "\"]:not(." + params.slideDuplicateClass + ")").eq(0).index();
        nextTick(function () {
          swiper.slideTo(slideToIndex);
        });
      } else {
        swiper.slideTo(slideToIndex);
      }
    } else {
      swiper.slideTo(slideToIndex);
    }
  }

  var slide = {
    slideTo: slideTo,
    slideToLoop: slideToLoop,
    slideNext: slideNext,
    slidePrev: slidePrev,
    slideReset: slideReset,
    slideToClosest: slideToClosest,
    slideToClickedSlide: slideToClickedSlide
  };

  function loopCreate() {
    var swiper = this;
    var document = getDocument();
    var params = swiper.params,
        $wrapperEl = swiper.$wrapperEl; // Remove duplicated slides

    $wrapperEl.children("." + params.slideClass + "." + params.slideDuplicateClass).remove();
    var slides = $wrapperEl.children("." + params.slideClass);

    if (params.loopFillGroupWithBlank) {
      var blankSlidesNum = params.slidesPerGroup - slides.length % params.slidesPerGroup;

      if (blankSlidesNum !== params.slidesPerGroup) {
        for (var i = 0; i < blankSlidesNum; i += 1) {
          var blankNode = $(document.createElement('div')).addClass(params.slideClass + " " + params.slideBlankClass);
          $wrapperEl.append(blankNode);
        }

        slides = $wrapperEl.children("." + params.slideClass);
      }
    }

    if (params.slidesPerView === 'auto' && !params.loopedSlides) params.loopedSlides = slides.length;
    swiper.loopedSlides = Math.ceil(parseFloat(params.loopedSlides || params.slidesPerView, 10));
    swiper.loopedSlides += params.loopAdditionalSlides;

    if (swiper.loopedSlides > slides.length) {
      swiper.loopedSlides = slides.length;
    }

    var prependSlides = [];
    var appendSlides = [];
    slides.each(function (el, index) {
      var slide = $(el);

      if (index < swiper.loopedSlides) {
        appendSlides.push(el);
      }

      if (index < slides.length && index >= slides.length - swiper.loopedSlides) {
        prependSlides.push(el);
      }

      slide.attr('data-swiper-slide-index', index);
    });

    for (var _i = 0; _i < appendSlides.length; _i += 1) {
      $wrapperEl.append($(appendSlides[_i].cloneNode(true)).addClass(params.slideDuplicateClass));
    }

    for (var _i2 = prependSlides.length - 1; _i2 >= 0; _i2 -= 1) {
      $wrapperEl.prepend($(prependSlides[_i2].cloneNode(true)).addClass(params.slideDuplicateClass));
    }
  }

  function loopFix() {
    var swiper = this;
    swiper.emit('beforeLoopFix');
    var activeIndex = swiper.activeIndex,
        slides = swiper.slides,
        loopedSlides = swiper.loopedSlides,
        allowSlidePrev = swiper.allowSlidePrev,
        allowSlideNext = swiper.allowSlideNext,
        snapGrid = swiper.snapGrid,
        rtl = swiper.rtlTranslate;
    var newIndex;
    swiper.allowSlidePrev = true;
    swiper.allowSlideNext = true;
    var snapTranslate = -snapGrid[activeIndex];
    var diff = snapTranslate - swiper.getTranslate(); // Fix For Negative Oversliding

    if (activeIndex < loopedSlides) {
      newIndex = slides.length - loopedSlides * 3 + activeIndex;
      newIndex += loopedSlides;
      var slideChanged = swiper.slideTo(newIndex, 0, false, true);

      if (slideChanged && diff !== 0) {
        swiper.setTranslate((rtl ? -swiper.translate : swiper.translate) - diff);
      }
    } else if (activeIndex >= slides.length - loopedSlides) {
      // Fix For Positive Oversliding
      newIndex = -slides.length + activeIndex + loopedSlides;
      newIndex += loopedSlides;

      var _slideChanged = swiper.slideTo(newIndex, 0, false, true);

      if (_slideChanged && diff !== 0) {
        swiper.setTranslate((rtl ? -swiper.translate : swiper.translate) - diff);
      }
    }

    swiper.allowSlidePrev = allowSlidePrev;
    swiper.allowSlideNext = allowSlideNext;
    swiper.emit('loopFix');
  }

  function loopDestroy() {
    var swiper = this;
    var $wrapperEl = swiper.$wrapperEl,
        params = swiper.params,
        slides = swiper.slides;
    $wrapperEl.children("." + params.slideClass + "." + params.slideDuplicateClass + ",." + params.slideClass + "." + params.slideBlankClass).remove();
    slides.removeAttr('data-swiper-slide-index');
  }

  var loop = {
    loopCreate: loopCreate,
    loopFix: loopFix,
    loopDestroy: loopDestroy
  };

  function setGrabCursor(moving) {
    var swiper = this;
    if (swiper.support.touch || !swiper.params.simulateTouch || swiper.params.watchOverflow && swiper.isLocked || swiper.params.cssMode) return;
    var el = swiper.el;
    el.style.cursor = 'move';
    el.style.cursor = moving ? '-webkit-grabbing' : '-webkit-grab';
    el.style.cursor = moving ? '-moz-grabbin' : '-moz-grab';
    el.style.cursor = moving ? 'grabbing' : 'grab';
  }

  function unsetGrabCursor() {
    var swiper = this;

    if (swiper.support.touch || swiper.params.watchOverflow && swiper.isLocked || swiper.params.cssMode) {
      return;
    }

    swiper.el.style.cursor = '';
  }

  var grabCursor = {
    setGrabCursor: setGrabCursor,
    unsetGrabCursor: unsetGrabCursor
  };

  function appendSlide(slides) {
    var swiper = this;
    var $wrapperEl = swiper.$wrapperEl,
        params = swiper.params;

    if (params.loop) {
      swiper.loopDestroy();
    }

    if (_typeof(slides) === 'object' && 'length' in slides) {
      for (var i = 0; i < slides.length; i += 1) {
        if (slides[i]) $wrapperEl.append(slides[i]);
      }
    } else {
      $wrapperEl.append(slides);
    }

    if (params.loop) {
      swiper.loopCreate();
    }

    if (!(params.observer && swiper.support.observer)) {
      swiper.update();
    }
  }

  function prependSlide(slides) {
    var swiper = this;
    var params = swiper.params,
        $wrapperEl = swiper.$wrapperEl,
        activeIndex = swiper.activeIndex;

    if (params.loop) {
      swiper.loopDestroy();
    }

    var newActiveIndex = activeIndex + 1;

    if (_typeof(slides) === 'object' && 'length' in slides) {
      for (var i = 0; i < slides.length; i += 1) {
        if (slides[i]) $wrapperEl.prepend(slides[i]);
      }

      newActiveIndex = activeIndex + slides.length;
    } else {
      $wrapperEl.prepend(slides);
    }

    if (params.loop) {
      swiper.loopCreate();
    }

    if (!(params.observer && swiper.support.observer)) {
      swiper.update();
    }

    swiper.slideTo(newActiveIndex, 0, false);
  }

  function addSlide(index, slides) {
    var swiper = this;
    var $wrapperEl = swiper.$wrapperEl,
        params = swiper.params,
        activeIndex = swiper.activeIndex;
    var activeIndexBuffer = activeIndex;

    if (params.loop) {
      activeIndexBuffer -= swiper.loopedSlides;
      swiper.loopDestroy();
      swiper.slides = $wrapperEl.children("." + params.slideClass);
    }

    var baseLength = swiper.slides.length;

    if (index <= 0) {
      swiper.prependSlide(slides);
      return;
    }

    if (index >= baseLength) {
      swiper.appendSlide(slides);
      return;
    }

    var newActiveIndex = activeIndexBuffer > index ? activeIndexBuffer + 1 : activeIndexBuffer;
    var slidesBuffer = [];

    for (var i = baseLength - 1; i >= index; i -= 1) {
      var currentSlide = swiper.slides.eq(i);
      currentSlide.remove();
      slidesBuffer.unshift(currentSlide);
    }

    if (_typeof(slides) === 'object' && 'length' in slides) {
      for (var _i = 0; _i < slides.length; _i += 1) {
        if (slides[_i]) $wrapperEl.append(slides[_i]);
      }

      newActiveIndex = activeIndexBuffer > index ? activeIndexBuffer + slides.length : activeIndexBuffer;
    } else {
      $wrapperEl.append(slides);
    }

    for (var _i2 = 0; _i2 < slidesBuffer.length; _i2 += 1) {
      $wrapperEl.append(slidesBuffer[_i2]);
    }

    if (params.loop) {
      swiper.loopCreate();
    }

    if (!(params.observer && swiper.support.observer)) {
      swiper.update();
    }

    if (params.loop) {
      swiper.slideTo(newActiveIndex + swiper.loopedSlides, 0, false);
    } else {
      swiper.slideTo(newActiveIndex, 0, false);
    }
  }

  function removeSlide(slidesIndexes) {
    var swiper = this;
    var params = swiper.params,
        $wrapperEl = swiper.$wrapperEl,
        activeIndex = swiper.activeIndex;
    var activeIndexBuffer = activeIndex;

    if (params.loop) {
      activeIndexBuffer -= swiper.loopedSlides;
      swiper.loopDestroy();
      swiper.slides = $wrapperEl.children("." + params.slideClass);
    }

    var newActiveIndex = activeIndexBuffer;
    var indexToRemove;

    if (_typeof(slidesIndexes) === 'object' && 'length' in slidesIndexes) {
      for (var i = 0; i < slidesIndexes.length; i += 1) {
        indexToRemove = slidesIndexes[i];
        if (swiper.slides[indexToRemove]) swiper.slides.eq(indexToRemove).remove();
        if (indexToRemove < newActiveIndex) newActiveIndex -= 1;
      }

      newActiveIndex = Math.max(newActiveIndex, 0);
    } else {
      indexToRemove = slidesIndexes;
      if (swiper.slides[indexToRemove]) swiper.slides.eq(indexToRemove).remove();
      if (indexToRemove < newActiveIndex) newActiveIndex -= 1;
      newActiveIndex = Math.max(newActiveIndex, 0);
    }

    if (params.loop) {
      swiper.loopCreate();
    }

    if (!(params.observer && swiper.support.observer)) {
      swiper.update();
    }

    if (params.loop) {
      swiper.slideTo(newActiveIndex + swiper.loopedSlides, 0, false);
    } else {
      swiper.slideTo(newActiveIndex, 0, false);
    }
  }

  function removeAllSlides() {
    var swiper = this;
    var slidesIndexes = [];

    for (var i = 0; i < swiper.slides.length; i += 1) {
      slidesIndexes.push(i);
    }

    swiper.removeSlide(slidesIndexes);
  }

  var manipulation = {
    appendSlide: appendSlide,
    prependSlide: prependSlide,
    addSlide: addSlide,
    removeSlide: removeSlide,
    removeAllSlides: removeAllSlides
  };

  function closestElement(selector, base) {
    if (base === void 0) {
      base = this;
    }

    function __closestFrom(el) {
      if (!el || el === getDocument() || el === getWindow()) return null;
      if (el.assignedSlot) el = el.assignedSlot;
      var found = el.closest(selector);
      return found || __closestFrom(el.getRootNode().host);
    }

    return __closestFrom(base);
  }

  function onTouchStart(event) {
    var swiper = this;
    var document = getDocument();
    var window = getWindow();
    var data = swiper.touchEventsData;
    var params = swiper.params,
        touches = swiper.touches,
        enabled = swiper.enabled;
    if (!enabled) return;

    if (swiper.animating && params.preventInteractionOnTransition) {
      return;
    }

    var e = event;
    if (e.originalEvent) e = e.originalEvent;
    var $targetEl = $(e.target);

    if (params.touchEventsTarget === 'wrapper') {
      if (!$targetEl.closest(swiper.wrapperEl).length) return;
    }

    data.isTouchEvent = e.type === 'touchstart';
    if (!data.isTouchEvent && 'which' in e && e.which === 3) return;
    if (!data.isTouchEvent && 'button' in e && e.button > 0) return;
    if (data.isTouched && data.isMoved) return; // change target el for shadow root component

    var swipingClassHasValue = !!params.noSwipingClass && params.noSwipingClass !== '';

    if (swipingClassHasValue && e.target && e.target.shadowRoot && event.path && event.path[0]) {
      $targetEl = $(event.path[0]);
    }

    var noSwipingSelector = params.noSwipingSelector ? params.noSwipingSelector : "." + params.noSwipingClass;
    var isTargetShadow = !!(e.target && e.target.shadowRoot); // use closestElement for shadow root element to get the actual closest for nested shadow root element

    if (params.noSwiping && (isTargetShadow ? closestElement(noSwipingSelector, e.target) : $targetEl.closest(noSwipingSelector)[0])) {
      swiper.allowClick = true;
      return;
    }

    if (params.swipeHandler) {
      if (!$targetEl.closest(params.swipeHandler)[0]) return;
    }

    touches.currentX = e.type === 'touchstart' ? e.targetTouches[0].pageX : e.pageX;
    touches.currentY = e.type === 'touchstart' ? e.targetTouches[0].pageY : e.pageY;
    var startX = touches.currentX;
    var startY = touches.currentY; // Do NOT start if iOS edge swipe is detected. Otherwise iOS app cannot swipe-to-go-back anymore

    var edgeSwipeDetection = params.edgeSwipeDetection || params.iOSEdgeSwipeDetection;
    var edgeSwipeThreshold = params.edgeSwipeThreshold || params.iOSEdgeSwipeThreshold;

    if (edgeSwipeDetection && (startX <= edgeSwipeThreshold || startX >= window.innerWidth - edgeSwipeThreshold)) {
      if (edgeSwipeDetection === 'prevent') {
        event.preventDefault();
      } else {
        return;
      }
    }

    extend(data, {
      isTouched: true,
      isMoved: false,
      allowTouchCallbacks: true,
      isScrolling: undefined,
      startMoving: undefined
    });
    touches.startX = startX;
    touches.startY = startY;
    data.touchStartTime = now();
    swiper.allowClick = true;
    swiper.updateSize();
    swiper.swipeDirection = undefined;
    if (params.threshold > 0) data.allowThresholdMove = false;

    if (e.type !== 'touchstart') {
      var preventDefault = true;
      if ($targetEl.is(data.focusableElements)) preventDefault = false;

      if (document.activeElement && $(document.activeElement).is(data.focusableElements) && document.activeElement !== $targetEl[0]) {
        document.activeElement.blur();
      }

      var shouldPreventDefault = preventDefault && swiper.allowTouchMove && params.touchStartPreventDefault;

      if ((params.touchStartForcePreventDefault || shouldPreventDefault) && !$targetEl[0].isContentEditable) {
        e.preventDefault();
      }
    }

    swiper.emit('touchStart', e);
  }

  function onTouchMove(event) {
    var document = getDocument();
    var swiper = this;
    var data = swiper.touchEventsData;
    var params = swiper.params,
        touches = swiper.touches,
        rtl = swiper.rtlTranslate,
        enabled = swiper.enabled;
    if (!enabled) return;
    var e = event;
    if (e.originalEvent) e = e.originalEvent;

    if (!data.isTouched) {
      if (data.startMoving && data.isScrolling) {
        swiper.emit('touchMoveOpposite', e);
      }

      return;
    }

    if (data.isTouchEvent && e.type !== 'touchmove') return;
    var targetTouch = e.type === 'touchmove' && e.targetTouches && (e.targetTouches[0] || e.changedTouches[0]);
    var pageX = e.type === 'touchmove' ? targetTouch.pageX : e.pageX;
    var pageY = e.type === 'touchmove' ? targetTouch.pageY : e.pageY;

    if (e.preventedByNestedSwiper) {
      touches.startX = pageX;
      touches.startY = pageY;
      return;
    }

    if (!swiper.allowTouchMove) {
      // isMoved = true;
      swiper.allowClick = false;

      if (data.isTouched) {
        extend(touches, {
          startX: pageX,
          startY: pageY,
          currentX: pageX,
          currentY: pageY
        });
        data.touchStartTime = now();
      }

      return;
    }

    if (data.isTouchEvent && params.touchReleaseOnEdges && !params.loop) {
      if (swiper.isVertical()) {
        // Vertical
        if (pageY < touches.startY && swiper.translate <= swiper.maxTranslate() || pageY > touches.startY && swiper.translate >= swiper.minTranslate()) {
          data.isTouched = false;
          data.isMoved = false;
          return;
        }
      } else if (pageX < touches.startX && swiper.translate <= swiper.maxTranslate() || pageX > touches.startX && swiper.translate >= swiper.minTranslate()) {
        return;
      }
    }

    if (data.isTouchEvent && document.activeElement) {
      if (e.target === document.activeElement && $(e.target).is(data.focusableElements)) {
        data.isMoved = true;
        swiper.allowClick = false;
        return;
      }
    }

    if (data.allowTouchCallbacks) {
      swiper.emit('touchMove', e);
    }

    if (e.targetTouches && e.targetTouches.length > 1) return;
    touches.currentX = pageX;
    touches.currentY = pageY;
    var diffX = touches.currentX - touches.startX;
    var diffY = touches.currentY - touches.startY;
    if (swiper.params.threshold && Math.sqrt(Math.pow(diffX, 2) + Math.pow(diffY, 2)) < swiper.params.threshold) return;

    if (typeof data.isScrolling === 'undefined') {
      var touchAngle;

      if (swiper.isHorizontal() && touches.currentY === touches.startY || swiper.isVertical() && touches.currentX === touches.startX) {
        data.isScrolling = false;
      } else {
        // eslint-disable-next-line
        if (diffX * diffX + diffY * diffY >= 25) {
          touchAngle = Math.atan2(Math.abs(diffY), Math.abs(diffX)) * 180 / Math.PI;
          data.isScrolling = swiper.isHorizontal() ? touchAngle > params.touchAngle : 90 - touchAngle > params.touchAngle;
        }
      }
    }

    if (data.isScrolling) {
      swiper.emit('touchMoveOpposite', e);
    }

    if (typeof data.startMoving === 'undefined') {
      if (touches.currentX !== touches.startX || touches.currentY !== touches.startY) {
        data.startMoving = true;
      }
    }

    if (data.isScrolling) {
      data.isTouched = false;
      return;
    }

    if (!data.startMoving) {
      return;
    }

    swiper.allowClick = false;

    if (!params.cssMode && e.cancelable) {
      e.preventDefault();
    }

    if (params.touchMoveStopPropagation && !params.nested) {
      e.stopPropagation();
    }

    if (!data.isMoved) {
      if (params.loop) {
        swiper.loopFix();
      }

      data.startTranslate = swiper.getTranslate();
      swiper.setTransition(0);

      if (swiper.animating) {
        swiper.$wrapperEl.trigger('webkitTransitionEnd transitionend');
      }

      data.allowMomentumBounce = false; // Grab Cursor

      if (params.grabCursor && (swiper.allowSlideNext === true || swiper.allowSlidePrev === true)) {
        swiper.setGrabCursor(true);
      }

      swiper.emit('sliderFirstMove', e);
    }

    swiper.emit('sliderMove', e);
    data.isMoved = true;
    var diff = swiper.isHorizontal() ? diffX : diffY;
    touches.diff = diff;
    diff *= params.touchRatio;
    if (rtl) diff = -diff;
    swiper.swipeDirection = diff > 0 ? 'prev' : 'next';
    data.currentTranslate = diff + data.startTranslate;
    var disableParentSwiper = true;
    var resistanceRatio = params.resistanceRatio;

    if (params.touchReleaseOnEdges) {
      resistanceRatio = 0;
    }

    if (diff > 0 && data.currentTranslate > swiper.minTranslate()) {
      disableParentSwiper = false;
      if (params.resistance) data.currentTranslate = swiper.minTranslate() - 1 + Math.pow(-swiper.minTranslate() + data.startTranslate + diff, resistanceRatio);
    } else if (diff < 0 && data.currentTranslate < swiper.maxTranslate()) {
      disableParentSwiper = false;
      if (params.resistance) data.currentTranslate = swiper.maxTranslate() + 1 - Math.pow(swiper.maxTranslate() - data.startTranslate - diff, resistanceRatio);
    }

    if (disableParentSwiper) {
      e.preventedByNestedSwiper = true;
    } // Directions locks


    if (!swiper.allowSlideNext && swiper.swipeDirection === 'next' && data.currentTranslate < data.startTranslate) {
      data.currentTranslate = data.startTranslate;
    }

    if (!swiper.allowSlidePrev && swiper.swipeDirection === 'prev' && data.currentTranslate > data.startTranslate) {
      data.currentTranslate = data.startTranslate;
    }

    if (!swiper.allowSlidePrev && !swiper.allowSlideNext) {
      data.currentTranslate = data.startTranslate;
    } // Threshold


    if (params.threshold > 0) {
      if (Math.abs(diff) > params.threshold || data.allowThresholdMove) {
        if (!data.allowThresholdMove) {
          data.allowThresholdMove = true;
          touches.startX = touches.currentX;
          touches.startY = touches.currentY;
          data.currentTranslate = data.startTranslate;
          touches.diff = swiper.isHorizontal() ? touches.currentX - touches.startX : touches.currentY - touches.startY;
          return;
        }
      } else {
        data.currentTranslate = data.startTranslate;
        return;
      }
    }

    if (!params.followFinger || params.cssMode) return; // Update active index in free mode

    if (params.freeMode || params.watchSlidesProgress || params.watchSlidesVisibility) {
      swiper.updateActiveIndex();
      swiper.updateSlidesClasses();
    }

    if (params.freeMode) {
      // Velocity
      if (data.velocities.length === 0) {
        data.velocities.push({
          position: touches[swiper.isHorizontal() ? 'startX' : 'startY'],
          time: data.touchStartTime
        });
      }

      data.velocities.push({
        position: touches[swiper.isHorizontal() ? 'currentX' : 'currentY'],
        time: now()
      });
    } // Update progress


    swiper.updateProgress(data.currentTranslate); // Update translate

    swiper.setTranslate(data.currentTranslate);
  }

  function onTouchEnd(event) {
    var swiper = this;
    var data = swiper.touchEventsData;
    var params = swiper.params,
        touches = swiper.touches,
        rtl = swiper.rtlTranslate,
        $wrapperEl = swiper.$wrapperEl,
        slidesGrid = swiper.slidesGrid,
        snapGrid = swiper.snapGrid,
        enabled = swiper.enabled;
    if (!enabled) return;
    var e = event;
    if (e.originalEvent) e = e.originalEvent;

    if (data.allowTouchCallbacks) {
      swiper.emit('touchEnd', e);
    }

    data.allowTouchCallbacks = false;

    if (!data.isTouched) {
      if (data.isMoved && params.grabCursor) {
        swiper.setGrabCursor(false);
      }

      data.isMoved = false;
      data.startMoving = false;
      return;
    } // Return Grab Cursor


    if (params.grabCursor && data.isMoved && data.isTouched && (swiper.allowSlideNext === true || swiper.allowSlidePrev === true)) {
      swiper.setGrabCursor(false);
    } // Time diff


    var touchEndTime = now();
    var timeDiff = touchEndTime - data.touchStartTime; // Tap, doubleTap, Click

    if (swiper.allowClick) {
      swiper.updateClickedSlide(e);
      swiper.emit('tap click', e);

      if (timeDiff < 300 && touchEndTime - data.lastClickTime < 300) {
        swiper.emit('doubleTap doubleClick', e);
      }
    }

    data.lastClickTime = now();
    nextTick(function () {
      if (!swiper.destroyed) swiper.allowClick = true;
    });

    if (!data.isTouched || !data.isMoved || !swiper.swipeDirection || touches.diff === 0 || data.currentTranslate === data.startTranslate) {
      data.isTouched = false;
      data.isMoved = false;
      data.startMoving = false;
      return;
    }

    data.isTouched = false;
    data.isMoved = false;
    data.startMoving = false;
    var currentPos;

    if (params.followFinger) {
      currentPos = rtl ? swiper.translate : -swiper.translate;
    } else {
      currentPos = -data.currentTranslate;
    }

    if (params.cssMode) {
      return;
    }

    if (params.freeMode) {
      if (currentPos < -swiper.minTranslate()) {
        swiper.slideTo(swiper.activeIndex);
        return;
      }

      if (currentPos > -swiper.maxTranslate()) {
        if (swiper.slides.length < snapGrid.length) {
          swiper.slideTo(snapGrid.length - 1);
        } else {
          swiper.slideTo(swiper.slides.length - 1);
        }

        return;
      }

      if (params.freeModeMomentum) {
        if (data.velocities.length > 1) {
          var lastMoveEvent = data.velocities.pop();
          var velocityEvent = data.velocities.pop();
          var distance = lastMoveEvent.position - velocityEvent.position;
          var time = lastMoveEvent.time - velocityEvent.time;
          swiper.velocity = distance / time;
          swiper.velocity /= 2;

          if (Math.abs(swiper.velocity) < params.freeModeMinimumVelocity) {
            swiper.velocity = 0;
          } // this implies that the user stopped moving a finger then released.
          // There would be no events with distance zero, so the last event is stale.


          if (time > 150 || now() - lastMoveEvent.time > 300) {
            swiper.velocity = 0;
          }
        } else {
          swiper.velocity = 0;
        }

        swiper.velocity *= params.freeModeMomentumVelocityRatio;
        data.velocities.length = 0;
        var momentumDuration = 1000 * params.freeModeMomentumRatio;
        var momentumDistance = swiper.velocity * momentumDuration;
        var newPosition = swiper.translate + momentumDistance;
        if (rtl) newPosition = -newPosition;
        var doBounce = false;
        var afterBouncePosition;
        var bounceAmount = Math.abs(swiper.velocity) * 20 * params.freeModeMomentumBounceRatio;
        var needsLoopFix;

        if (newPosition < swiper.maxTranslate()) {
          if (params.freeModeMomentumBounce) {
            if (newPosition + swiper.maxTranslate() < -bounceAmount) {
              newPosition = swiper.maxTranslate() - bounceAmount;
            }

            afterBouncePosition = swiper.maxTranslate();
            doBounce = true;
            data.allowMomentumBounce = true;
          } else {
            newPosition = swiper.maxTranslate();
          }

          if (params.loop && params.centeredSlides) needsLoopFix = true;
        } else if (newPosition > swiper.minTranslate()) {
          if (params.freeModeMomentumBounce) {
            if (newPosition - swiper.minTranslate() > bounceAmount) {
              newPosition = swiper.minTranslate() + bounceAmount;
            }

            afterBouncePosition = swiper.minTranslate();
            doBounce = true;
            data.allowMomentumBounce = true;
          } else {
            newPosition = swiper.minTranslate();
          }

          if (params.loop && params.centeredSlides) needsLoopFix = true;
        } else if (params.freeModeSticky) {
          var nextSlide;

          for (var j = 0; j < snapGrid.length; j += 1) {
            if (snapGrid[j] > -newPosition) {
              nextSlide = j;
              break;
            }
          }

          if (Math.abs(snapGrid[nextSlide] - newPosition) < Math.abs(snapGrid[nextSlide - 1] - newPosition) || swiper.swipeDirection === 'next') {
            newPosition = snapGrid[nextSlide];
          } else {
            newPosition = snapGrid[nextSlide - 1];
          }

          newPosition = -newPosition;
        }

        if (needsLoopFix) {
          swiper.once('transitionEnd', function () {
            swiper.loopFix();
          });
        } // Fix duration


        if (swiper.velocity !== 0) {
          if (rtl) {
            momentumDuration = Math.abs((-newPosition - swiper.translate) / swiper.velocity);
          } else {
            momentumDuration = Math.abs((newPosition - swiper.translate) / swiper.velocity);
          }

          if (params.freeModeSticky) {
            // If freeModeSticky is active and the user ends a swipe with a slow-velocity
            // event, then durations can be 20+ seconds to slide one (or zero!) slides.
            // It's easy to see this when simulating touch with mouse events. To fix this,
            // limit single-slide swipes to the default slide duration. This also has the
            // nice side effect of matching slide speed if the user stopped moving before
            // lifting finger or mouse vs. moving slowly before lifting the finger/mouse.
            // For faster swipes, also apply limits (albeit higher ones).
            var moveDistance = Math.abs((rtl ? -newPosition : newPosition) - swiper.translate);
            var currentSlideSize = swiper.slidesSizesGrid[swiper.activeIndex];

            if (moveDistance < currentSlideSize) {
              momentumDuration = params.speed;
            } else if (moveDistance < 2 * currentSlideSize) {
              momentumDuration = params.speed * 1.5;
            } else {
              momentumDuration = params.speed * 2.5;
            }
          }
        } else if (params.freeModeSticky) {
          swiper.slideToClosest();
          return;
        }

        if (params.freeModeMomentumBounce && doBounce) {
          swiper.updateProgress(afterBouncePosition);
          swiper.setTransition(momentumDuration);
          swiper.setTranslate(newPosition);
          swiper.transitionStart(true, swiper.swipeDirection);
          swiper.animating = true;
          $wrapperEl.transitionEnd(function () {
            if (!swiper || swiper.destroyed || !data.allowMomentumBounce) return;
            swiper.emit('momentumBounce');
            swiper.setTransition(params.speed);
            setTimeout(function () {
              swiper.setTranslate(afterBouncePosition);
              $wrapperEl.transitionEnd(function () {
                if (!swiper || swiper.destroyed) return;
                swiper.transitionEnd();
              });
            }, 0);
          });
        } else if (swiper.velocity) {
          swiper.updateProgress(newPosition);
          swiper.setTransition(momentumDuration);
          swiper.setTranslate(newPosition);
          swiper.transitionStart(true, swiper.swipeDirection);

          if (!swiper.animating) {
            swiper.animating = true;
            $wrapperEl.transitionEnd(function () {
              if (!swiper || swiper.destroyed) return;
              swiper.transitionEnd();
            });
          }
        } else {
          swiper.emit('_freeModeNoMomentumRelease');
          swiper.updateProgress(newPosition);
        }

        swiper.updateActiveIndex();
        swiper.updateSlidesClasses();
      } else if (params.freeModeSticky) {
        swiper.slideToClosest();
        return;
      } else if (params.freeMode) {
        swiper.emit('_freeModeNoMomentumRelease');
      }

      if (!params.freeModeMomentum || timeDiff >= params.longSwipesMs) {
        swiper.updateProgress();
        swiper.updateActiveIndex();
        swiper.updateSlidesClasses();
      }

      return;
    } // Find current slide


    var stopIndex = 0;
    var groupSize = swiper.slidesSizesGrid[0];

    for (var i = 0; i < slidesGrid.length; i += i < params.slidesPerGroupSkip ? 1 : params.slidesPerGroup) {
      var _increment = i < params.slidesPerGroupSkip - 1 ? 1 : params.slidesPerGroup;

      if (typeof slidesGrid[i + _increment] !== 'undefined') {
        if (currentPos >= slidesGrid[i] && currentPos < slidesGrid[i + _increment]) {
          stopIndex = i;
          groupSize = slidesGrid[i + _increment] - slidesGrid[i];
        }
      } else if (currentPos >= slidesGrid[i]) {
        stopIndex = i;
        groupSize = slidesGrid[slidesGrid.length - 1] - slidesGrid[slidesGrid.length - 2];
      }
    } // Find current slide size


    var ratio = (currentPos - slidesGrid[stopIndex]) / groupSize;
    var increment = stopIndex < params.slidesPerGroupSkip - 1 ? 1 : params.slidesPerGroup;

    if (timeDiff > params.longSwipesMs) {
      // Long touches
      if (!params.longSwipes) {
        swiper.slideTo(swiper.activeIndex);
        return;
      }

      if (swiper.swipeDirection === 'next') {
        if (ratio >= params.longSwipesRatio) swiper.slideTo(stopIndex + increment);else swiper.slideTo(stopIndex);
      }

      if (swiper.swipeDirection === 'prev') {
        if (ratio > 1 - params.longSwipesRatio) swiper.slideTo(stopIndex + increment);else swiper.slideTo(stopIndex);
      }
    } else {
      // Short swipes
      if (!params.shortSwipes) {
        swiper.slideTo(swiper.activeIndex);
        return;
      }

      var isNavButtonTarget = swiper.navigation && (e.target === swiper.navigation.nextEl || e.target === swiper.navigation.prevEl);

      if (!isNavButtonTarget) {
        if (swiper.swipeDirection === 'next') {
          swiper.slideTo(stopIndex + increment);
        }

        if (swiper.swipeDirection === 'prev') {
          swiper.slideTo(stopIndex);
        }
      } else if (e.target === swiper.navigation.nextEl) {
        swiper.slideTo(stopIndex + increment);
      } else {
        swiper.slideTo(stopIndex);
      }
    }
  }

  function onResize() {
    var swiper = this;
    var params = swiper.params,
        el = swiper.el;
    if (el && el.offsetWidth === 0) return; // Breakpoints

    if (params.breakpoints) {
      swiper.setBreakpoint();
    } // Save locks


    var allowSlideNext = swiper.allowSlideNext,
        allowSlidePrev = swiper.allowSlidePrev,
        snapGrid = swiper.snapGrid; // Disable locks on resize

    swiper.allowSlideNext = true;
    swiper.allowSlidePrev = true;
    swiper.updateSize();
    swiper.updateSlides();
    swiper.updateSlidesClasses();

    if ((params.slidesPerView === 'auto' || params.slidesPerView > 1) && swiper.isEnd && !swiper.isBeginning && !swiper.params.centeredSlides) {
      swiper.slideTo(swiper.slides.length - 1, 0, false, true);
    } else {
      swiper.slideTo(swiper.activeIndex, 0, false, true);
    }

    if (swiper.autoplay && swiper.autoplay.running && swiper.autoplay.paused) {
      swiper.autoplay.run();
    } // Return locks after resize


    swiper.allowSlidePrev = allowSlidePrev;
    swiper.allowSlideNext = allowSlideNext;

    if (swiper.params.watchOverflow && snapGrid !== swiper.snapGrid) {
      swiper.checkOverflow();
    }
  }

  function onClick(e) {
    var swiper = this;
    if (!swiper.enabled) return;

    if (!swiper.allowClick) {
      if (swiper.params.preventClicks) e.preventDefault();

      if (swiper.params.preventClicksPropagation && swiper.animating) {
        e.stopPropagation();
        e.stopImmediatePropagation();
      }
    }
  }

  function onScroll() {
    var swiper = this;
    var wrapperEl = swiper.wrapperEl,
        rtlTranslate = swiper.rtlTranslate,
        enabled = swiper.enabled;
    if (!enabled) return;
    swiper.previousTranslate = swiper.translate;

    if (swiper.isHorizontal()) {
      if (rtlTranslate) {
        swiper.translate = wrapperEl.scrollWidth - wrapperEl.offsetWidth - wrapperEl.scrollLeft;
      } else {
        swiper.translate = -wrapperEl.scrollLeft;
      }
    } else {
      swiper.translate = -wrapperEl.scrollTop;
    } // eslint-disable-next-line


    if (swiper.translate === -0) swiper.translate = 0;
    swiper.updateActiveIndex();
    swiper.updateSlidesClasses();
    var newProgress;
    var translatesDiff = swiper.maxTranslate() - swiper.minTranslate();

    if (translatesDiff === 0) {
      newProgress = 0;
    } else {
      newProgress = (swiper.translate - swiper.minTranslate()) / translatesDiff;
    }

    if (newProgress !== swiper.progress) {
      swiper.updateProgress(rtlTranslate ? -swiper.translate : swiper.translate);
    }

    swiper.emit('setTranslate', swiper.translate, false);
  }

  var dummyEventAttached = false;

  function dummyEventListener() {}

  function attachEvents() {
    var swiper = this;
    var document = getDocument();
    var params = swiper.params,
        touchEvents = swiper.touchEvents,
        el = swiper.el,
        wrapperEl = swiper.wrapperEl,
        device = swiper.device,
        support = swiper.support;
    swiper.onTouchStart = onTouchStart.bind(swiper);
    swiper.onTouchMove = onTouchMove.bind(swiper);
    swiper.onTouchEnd = onTouchEnd.bind(swiper);

    if (params.cssMode) {
      swiper.onScroll = onScroll.bind(swiper);
    }

    swiper.onClick = onClick.bind(swiper);
    var capture = !!params.nested; // Touch Events

    if (!support.touch && support.pointerEvents) {
      el.addEventListener(touchEvents.start, swiper.onTouchStart, false);
      document.addEventListener(touchEvents.move, swiper.onTouchMove, capture);
      document.addEventListener(touchEvents.end, swiper.onTouchEnd, false);
    } else {
      if (support.touch) {
        var passiveListener = touchEvents.start === 'touchstart' && support.passiveListener && params.passiveListeners ? {
          passive: true,
          capture: false
        } : false;
        el.addEventListener(touchEvents.start, swiper.onTouchStart, passiveListener);
        el.addEventListener(touchEvents.move, swiper.onTouchMove, support.passiveListener ? {
          passive: false,
          capture: capture
        } : capture);
        el.addEventListener(touchEvents.end, swiper.onTouchEnd, passiveListener);

        if (touchEvents.cancel) {
          el.addEventListener(touchEvents.cancel, swiper.onTouchEnd, passiveListener);
        }

        if (!dummyEventAttached) {
          document.addEventListener('touchstart', dummyEventListener);
          dummyEventAttached = true;
        }
      }

      if (params.simulateTouch && !device.ios && !device.android || params.simulateTouch && !support.touch && device.ios) {
        el.addEventListener('mousedown', swiper.onTouchStart, false);
        document.addEventListener('mousemove', swiper.onTouchMove, capture);
        document.addEventListener('mouseup', swiper.onTouchEnd, false);
      }
    } // Prevent Links Clicks


    if (params.preventClicks || params.preventClicksPropagation) {
      el.addEventListener('click', swiper.onClick, true);
    }

    if (params.cssMode) {
      wrapperEl.addEventListener('scroll', swiper.onScroll);
    } // Resize handler


    if (params.updateOnWindowResize) {
      swiper.on(device.ios || device.android ? 'resize orientationchange observerUpdate' : 'resize observerUpdate', onResize, true);
    } else {
      swiper.on('observerUpdate', onResize, true);
    }
  }

  function detachEvents() {
    var swiper = this;
    var document = getDocument();
    var params = swiper.params,
        touchEvents = swiper.touchEvents,
        el = swiper.el,
        wrapperEl = swiper.wrapperEl,
        device = swiper.device,
        support = swiper.support;
    var capture = !!params.nested; // Touch Events

    if (!support.touch && support.pointerEvents) {
      el.removeEventListener(touchEvents.start, swiper.onTouchStart, false);
      document.removeEventListener(touchEvents.move, swiper.onTouchMove, capture);
      document.removeEventListener(touchEvents.end, swiper.onTouchEnd, false);
    } else {
      if (support.touch) {
        var passiveListener = touchEvents.start === 'onTouchStart' && support.passiveListener && params.passiveListeners ? {
          passive: true,
          capture: false
        } : false;
        el.removeEventListener(touchEvents.start, swiper.onTouchStart, passiveListener);
        el.removeEventListener(touchEvents.move, swiper.onTouchMove, capture);
        el.removeEventListener(touchEvents.end, swiper.onTouchEnd, passiveListener);

        if (touchEvents.cancel) {
          el.removeEventListener(touchEvents.cancel, swiper.onTouchEnd, passiveListener);
        }
      }

      if (params.simulateTouch && !device.ios && !device.android || params.simulateTouch && !support.touch && device.ios) {
        el.removeEventListener('mousedown', swiper.onTouchStart, false);
        document.removeEventListener('mousemove', swiper.onTouchMove, capture);
        document.removeEventListener('mouseup', swiper.onTouchEnd, false);
      }
    } // Prevent Links Clicks


    if (params.preventClicks || params.preventClicksPropagation) {
      el.removeEventListener('click', swiper.onClick, true);
    }

    if (params.cssMode) {
      wrapperEl.removeEventListener('scroll', swiper.onScroll);
    } // Resize handler


    swiper.off(device.ios || device.android ? 'resize orientationchange observerUpdate' : 'resize observerUpdate', onResize);
  }

  var events = {
    attachEvents: attachEvents,
    detachEvents: detachEvents
  };

  function setBreakpoint() {
    var swiper = this;
    var activeIndex = swiper.activeIndex,
        initialized = swiper.initialized,
        _swiper$loopedSlides = swiper.loopedSlides,
        loopedSlides = _swiper$loopedSlides === void 0 ? 0 : _swiper$loopedSlides,
        params = swiper.params,
        $el = swiper.$el;
    var breakpoints = params.breakpoints;
    if (!breakpoints || breakpoints && Object.keys(breakpoints).length === 0) return; // Get breakpoint for window width and update parameters

    var breakpoint = swiper.getBreakpoint(breakpoints, swiper.params.breakpointsBase, swiper.el);
    if (!breakpoint || swiper.currentBreakpoint === breakpoint) return;
    var breakpointOnlyParams = breakpoint in breakpoints ? breakpoints[breakpoint] : undefined;

    if (breakpointOnlyParams) {
      ['slidesPerView', 'spaceBetween', 'slidesPerGroup', 'slidesPerGroupSkip', 'slidesPerColumn'].forEach(function (param) {
        var paramValue = breakpointOnlyParams[param];
        if (typeof paramValue === 'undefined') return;

        if (param === 'slidesPerView' && (paramValue === 'AUTO' || paramValue === 'auto')) {
          breakpointOnlyParams[param] = 'auto';
        } else if (param === 'slidesPerView') {
          breakpointOnlyParams[param] = parseFloat(paramValue);
        } else {
          breakpointOnlyParams[param] = parseInt(paramValue, 10);
        }
      });
    }

    var breakpointParams = breakpointOnlyParams || swiper.originalParams;
    var wasMultiRow = params.slidesPerColumn > 1;
    var isMultiRow = breakpointParams.slidesPerColumn > 1;
    var wasEnabled = params.enabled;

    if (wasMultiRow && !isMultiRow) {
      $el.removeClass(params.containerModifierClass + "multirow " + params.containerModifierClass + "multirow-column");
      swiper.emitContainerClasses();
    } else if (!wasMultiRow && isMultiRow) {
      $el.addClass(params.containerModifierClass + "multirow");

      if (breakpointParams.slidesPerColumnFill && breakpointParams.slidesPerColumnFill === 'column' || !breakpointParams.slidesPerColumnFill && params.slidesPerColumnFill === 'column') {
        $el.addClass(params.containerModifierClass + "multirow-column");
      }

      swiper.emitContainerClasses();
    }

    var directionChanged = breakpointParams.direction && breakpointParams.direction !== params.direction;
    var needsReLoop = params.loop && (breakpointParams.slidesPerView !== params.slidesPerView || directionChanged);

    if (directionChanged && initialized) {
      swiper.changeDirection();
    }

    extend(swiper.params, breakpointParams);
    var isEnabled = swiper.params.enabled;
    extend(swiper, {
      allowTouchMove: swiper.params.allowTouchMove,
      allowSlideNext: swiper.params.allowSlideNext,
      allowSlidePrev: swiper.params.allowSlidePrev
    });

    if (wasEnabled && !isEnabled) {
      swiper.disable();
    } else if (!wasEnabled && isEnabled) {
      swiper.enable();
    }

    swiper.currentBreakpoint = breakpoint;
    swiper.emit('_beforeBreakpoint', breakpointParams);

    if (needsReLoop && initialized) {
      swiper.loopDestroy();
      swiper.loopCreate();
      swiper.updateSlides();
      swiper.slideTo(activeIndex - loopedSlides + swiper.loopedSlides, 0, false);
    }

    swiper.emit('breakpoint', breakpointParams);
  }

  function getBreakpoint(breakpoints, base, containerEl) {
    if (base === void 0) {
      base = 'window';
    }

    if (!breakpoints || base === 'container' && !containerEl) return undefined;
    var breakpoint = false;
    var window = getWindow();
    var currentHeight = base === 'window' ? window.innerHeight : containerEl.clientHeight;
    var points = Object.keys(breakpoints).map(function (point) {
      if (typeof point === 'string' && point.indexOf('@') === 0) {
        var minRatio = parseFloat(point.substr(1));
        var value = currentHeight * minRatio;
        return {
          value: value,
          point: point
        };
      }

      return {
        value: point,
        point: point
      };
    });
    points.sort(function (a, b) {
      return parseInt(a.value, 10) - parseInt(b.value, 10);
    });

    for (var i = 0; i < points.length; i += 1) {
      var _points$i = points[i],
          point = _points$i.point,
          value = _points$i.value;

      if (base === 'window') {
        if (window.matchMedia("(min-width: " + value + "px)").matches) {
          breakpoint = point;
        }
      } else if (value <= containerEl.clientWidth) {
        breakpoint = point;
      }
    }

    return breakpoint || 'max';
  }

  var breakpoints = {
    setBreakpoint: setBreakpoint,
    getBreakpoint: getBreakpoint
  };

  function prepareClasses(entries, prefix) {
    var resultClasses = [];
    entries.forEach(function (item) {
      if (_typeof(item) === 'object') {
        Object.keys(item).forEach(function (classNames) {
          if (item[classNames]) {
            resultClasses.push(prefix + classNames);
          }
        });
      } else if (typeof item === 'string') {
        resultClasses.push(prefix + item);
      }
    });
    return resultClasses;
  }

  function addClasses() {
    var swiper = this;
    var classNames = swiper.classNames,
        params = swiper.params,
        rtl = swiper.rtl,
        $el = swiper.$el,
        device = swiper.device,
        support = swiper.support; // prettier-ignore

    var suffixes = prepareClasses(['initialized', params.direction, {
      'pointer-events': support.pointerEvents && !support.touch
    }, {
      'free-mode': params.freeMode
    }, {
      'autoheight': params.autoHeight
    }, {
      'rtl': rtl
    }, {
      'multirow': params.slidesPerColumn > 1
    }, {
      'multirow-column': params.slidesPerColumn > 1 && params.slidesPerColumnFill === 'column'
    }, {
      'android': device.android
    }, {
      'ios': device.ios
    }, {
      'css-mode': params.cssMode
    }], params.containerModifierClass);
    classNames.push.apply(classNames, suffixes);
    $el.addClass([].concat(classNames).join(' '));
    swiper.emitContainerClasses();
  }

  function removeClasses() {
    var swiper = this;
    var $el = swiper.$el,
        classNames = swiper.classNames;
    $el.removeClass(classNames.join(' '));
    swiper.emitContainerClasses();
  }

  var classes = {
    addClasses: addClasses,
    removeClasses: removeClasses
  };

  function loadImage(imageEl, src, srcset, sizes, checkForComplete, callback) {
    var window = getWindow();
    var image;

    function onReady() {
      if (callback) callback();
    }

    var isPicture = $(imageEl).parent('picture')[0];

    if (!isPicture && (!imageEl.complete || !checkForComplete)) {
      if (src) {
        image = new window.Image();
        image.onload = onReady;
        image.onerror = onReady;

        if (sizes) {
          image.sizes = sizes;
        }

        if (srcset) {
          image.srcset = srcset;
        }

        if (src) {
          image.src = src;
        }
      } else {
        onReady();
      }
    } else {
      // image already loaded...
      onReady();
    }
  }

  function preloadImages() {
    var swiper = this;
    swiper.imagesToLoad = swiper.$el.find('img');

    function onReady() {
      if (typeof swiper === 'undefined' || swiper === null || !swiper || swiper.destroyed) return;
      if (swiper.imagesLoaded !== undefined) swiper.imagesLoaded += 1;

      if (swiper.imagesLoaded === swiper.imagesToLoad.length) {
        if (swiper.params.updateOnImagesReady) swiper.update();
        swiper.emit('imagesReady');
      }
    }

    for (var i = 0; i < swiper.imagesToLoad.length; i += 1) {
      var imageEl = swiper.imagesToLoad[i];
      swiper.loadImage(imageEl, imageEl.currentSrc || imageEl.getAttribute('src'), imageEl.srcset || imageEl.getAttribute('srcset'), imageEl.sizes || imageEl.getAttribute('sizes'), true, onReady);
    }
  }

  var images = {
    loadImage: loadImage,
    preloadImages: preloadImages
  };

  function checkOverflow() {
    var swiper = this;
    var params = swiper.params;
    var wasLocked = swiper.isLocked;
    var lastSlidePosition = swiper.slides.length > 0 && params.slidesOffsetBefore + params.spaceBetween * (swiper.slides.length - 1) + swiper.slides[0].offsetWidth * swiper.slides.length;

    if (params.slidesOffsetBefore && params.slidesOffsetAfter && lastSlidePosition) {
      swiper.isLocked = lastSlidePosition <= swiper.size;
    } else {
      swiper.isLocked = swiper.snapGrid.length === 1;
    }

    swiper.allowSlideNext = !swiper.isLocked;
    swiper.allowSlidePrev = !swiper.isLocked; // events

    if (wasLocked !== swiper.isLocked) swiper.emit(swiper.isLocked ? 'lock' : 'unlock');

    if (wasLocked && wasLocked !== swiper.isLocked) {
      swiper.isEnd = false;
      if (swiper.navigation) swiper.navigation.update();
    }
  }

  var checkOverflow$1 = {
    checkOverflow: checkOverflow
  };
  var defaults = {
    init: true,
    direction: 'horizontal',
    touchEventsTarget: 'container',
    initialSlide: 0,
    speed: 300,
    cssMode: false,
    updateOnWindowResize: true,
    resizeObserver: false,
    nested: false,
    createElements: false,
    enabled: true,
    focusableElements: 'input, select, option, textarea, button, video, label',
    // Overrides
    width: null,
    height: null,
    //
    preventInteractionOnTransition: false,
    // ssr
    userAgent: null,
    url: null,
    // To support iOS's swipe-to-go-back gesture (when being used in-app).
    edgeSwipeDetection: false,
    edgeSwipeThreshold: 20,
    // Free mode
    freeMode: false,
    freeModeMomentum: true,
    freeModeMomentumRatio: 1,
    freeModeMomentumBounce: true,
    freeModeMomentumBounceRatio: 1,
    freeModeMomentumVelocityRatio: 1,
    freeModeSticky: false,
    freeModeMinimumVelocity: 0.02,
    // Autoheight
    autoHeight: false,
    // Set wrapper width
    setWrapperSize: false,
    // Virtual Translate
    virtualTranslate: false,
    // Effects
    effect: 'slide',
    // 'slide' or 'fade' or 'cube' or 'coverflow' or 'flip'
    // Breakpoints
    breakpoints: undefined,
    breakpointsBase: 'window',
    // Slides grid
    spaceBetween: 0,
    slidesPerView: 1,
    slidesPerColumn: 1,
    slidesPerColumnFill: 'column',
    slidesPerGroup: 1,
    slidesPerGroupSkip: 0,
    centeredSlides: false,
    centeredSlidesBounds: false,
    slidesOffsetBefore: 0,
    // in px
    slidesOffsetAfter: 0,
    // in px
    normalizeSlideIndex: true,
    centerInsufficientSlides: false,
    // Disable swiper and hide navigation when container not overflow
    watchOverflow: false,
    // Round length
    roundLengths: false,
    // Touches
    touchRatio: 1,
    touchAngle: 45,
    simulateTouch: true,
    shortSwipes: true,
    longSwipes: true,
    longSwipesRatio: 0.5,
    longSwipesMs: 300,
    followFinger: true,
    allowTouchMove: true,
    threshold: 0,
    touchMoveStopPropagation: false,
    touchStartPreventDefault: true,
    touchStartForcePreventDefault: false,
    touchReleaseOnEdges: false,
    // Unique Navigation Elements
    uniqueNavElements: true,
    // Resistance
    resistance: true,
    resistanceRatio: 0.85,
    // Progress
    watchSlidesProgress: false,
    watchSlidesVisibility: false,
    // Cursor
    grabCursor: false,
    // Clicks
    preventClicks: true,
    preventClicksPropagation: true,
    slideToClickedSlide: false,
    // Images
    preloadImages: true,
    updateOnImagesReady: true,
    // loop
    loop: false,
    loopAdditionalSlides: 0,
    loopedSlides: null,
    loopFillGroupWithBlank: false,
    loopPreventsSlide: true,
    // Swiping/no swiping
    allowSlidePrev: true,
    allowSlideNext: true,
    swipeHandler: null,
    // '.swipe-handler',
    noSwiping: true,
    noSwipingClass: 'swiper-no-swiping',
    noSwipingSelector: null,
    // Passive Listeners
    passiveListeners: true,
    // NS
    containerModifierClass: 'swiper-container-',
    // NEW
    slideClass: 'swiper-slide',
    slideBlankClass: 'swiper-slide-invisible-blank',
    slideActiveClass: 'swiper-slide-active',
    slideDuplicateActiveClass: 'swiper-slide-duplicate-active',
    slideVisibleClass: 'swiper-slide-visible',
    slideDuplicateClass: 'swiper-slide-duplicate',
    slideNextClass: 'swiper-slide-next',
    slideDuplicateNextClass: 'swiper-slide-duplicate-next',
    slidePrevClass: 'swiper-slide-prev',
    slideDuplicatePrevClass: 'swiper-slide-duplicate-prev',
    wrapperClass: 'swiper-wrapper',
    // Callbacks
    runCallbacksOnInit: true,
    // Internals
    _emitClasses: false
  };
  var prototypes = {
    modular: modular,
    eventsEmitter: eventsEmitter,
    update: update,
    translate: translate,
    transition: transition,
    slide: slide,
    loop: loop,
    grabCursor: grabCursor,
    manipulation: manipulation,
    events: events,
    breakpoints: breakpoints,
    checkOverflow: checkOverflow$1,
    classes: classes,
    images: images
  };
  var extendedDefaults = {};

  var Swiper = /*#__PURE__*/function () {
    function Swiper() {
      var el;
      var params;

      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }

      if (args.length === 1 && args[0].constructor && Object.prototype.toString.call(args[0]).slice(8, -1) === 'Object') {
        params = args[0];
      } else {
        el = args[0];
        params = args[1];
      }

      if (!params) params = {};
      params = extend({}, params);
      if (el && !params.el) params.el = el;

      if (params.el && $(params.el).length > 1) {
        var swipers = [];
        $(params.el).each(function (containerEl) {
          var newParams = extend({}, params, {
            el: containerEl
          });
          swipers.push(new Swiper(newParams));
        });
        return swipers;
      } // Swiper Instance


      var swiper = this;
      swiper.__swiper__ = true;
      swiper.support = getSupport();
      swiper.device = getDevice({
        userAgent: params.userAgent
      });
      swiper.browser = getBrowser();
      swiper.eventsListeners = {};
      swiper.eventsAnyListeners = [];

      if (typeof swiper.modules === 'undefined') {
        swiper.modules = {};
      }

      Object.keys(swiper.modules).forEach(function (moduleName) {
        var module = swiper.modules[moduleName];

        if (module.params) {
          var moduleParamName = Object.keys(module.params)[0];
          var moduleParams = module.params[moduleParamName];
          if (_typeof(moduleParams) !== 'object' || moduleParams === null) return;

          if (['navigation', 'pagination', 'scrollbar'].indexOf(moduleParamName) >= 0 && params[moduleParamName] === true) {
            params[moduleParamName] = {
              auto: true
            };
          }

          if (!(moduleParamName in params && 'enabled' in moduleParams)) return;

          if (params[moduleParamName] === true) {
            params[moduleParamName] = {
              enabled: true
            };
          }

          if (_typeof(params[moduleParamName]) === 'object' && !('enabled' in params[moduleParamName])) {
            params[moduleParamName].enabled = true;
          }

          if (!params[moduleParamName]) params[moduleParamName] = {
            enabled: false
          };
        }
      }); // Extend defaults with modules params

      var swiperParams = extend({}, defaults);
      swiper.useParams(swiperParams); // Extend defaults with passed params

      swiper.params = extend({}, swiperParams, extendedDefaults, params);
      swiper.originalParams = extend({}, swiper.params);
      swiper.passedParams = extend({}, params); // add event listeners

      if (swiper.params && swiper.params.on) {
        Object.keys(swiper.params.on).forEach(function (eventName) {
          swiper.on(eventName, swiper.params.on[eventName]);
        });
      }

      if (swiper.params && swiper.params.onAny) {
        swiper.onAny(swiper.params.onAny);
      } // Save Dom lib


      swiper.$ = $; // Extend Swiper

      extend(swiper, {
        enabled: swiper.params.enabled,
        el: el,
        // Classes
        classNames: [],
        // Slides
        slides: $(),
        slidesGrid: [],
        snapGrid: [],
        slidesSizesGrid: [],
        // isDirection
        isHorizontal: function isHorizontal() {
          return swiper.params.direction === 'horizontal';
        },
        isVertical: function isVertical() {
          return swiper.params.direction === 'vertical';
        },
        // Indexes
        activeIndex: 0,
        realIndex: 0,
        //
        isBeginning: true,
        isEnd: false,
        // Props
        translate: 0,
        previousTranslate: 0,
        progress: 0,
        velocity: 0,
        animating: false,
        // Locks
        allowSlideNext: swiper.params.allowSlideNext,
        allowSlidePrev: swiper.params.allowSlidePrev,
        // Touch Events
        touchEvents: function touchEvents() {
          var touch = ['touchstart', 'touchmove', 'touchend', 'touchcancel'];
          var desktop = ['mousedown', 'mousemove', 'mouseup'];

          if (swiper.support.pointerEvents) {
            desktop = ['pointerdown', 'pointermove', 'pointerup'];
          }

          swiper.touchEventsTouch = {
            start: touch[0],
            move: touch[1],
            end: touch[2],
            cancel: touch[3]
          };
          swiper.touchEventsDesktop = {
            start: desktop[0],
            move: desktop[1],
            end: desktop[2]
          };
          return swiper.support.touch || !swiper.params.simulateTouch ? swiper.touchEventsTouch : swiper.touchEventsDesktop;
        }(),
        touchEventsData: {
          isTouched: undefined,
          isMoved: undefined,
          allowTouchCallbacks: undefined,
          touchStartTime: undefined,
          isScrolling: undefined,
          currentTranslate: undefined,
          startTranslate: undefined,
          allowThresholdMove: undefined,
          // Form elements to match
          focusableElements: swiper.params.focusableElements,
          // Last click time
          lastClickTime: now(),
          clickTimeout: undefined,
          // Velocities
          velocities: [],
          allowMomentumBounce: undefined,
          isTouchEvent: undefined,
          startMoving: undefined
        },
        // Clicks
        allowClick: true,
        // Touches
        allowTouchMove: swiper.params.allowTouchMove,
        touches: {
          startX: 0,
          startY: 0,
          currentX: 0,
          currentY: 0,
          diff: 0
        },
        // Images
        imagesToLoad: [],
        imagesLoaded: 0
      }); // Install Modules

      swiper.useModules();
      swiper.emit('_swiper'); // Init

      if (swiper.params.init) {
        swiper.init();
      } // Return app instance


      return swiper;
    }

    var _proto = Swiper.prototype;

    _proto.enable = function enable() {
      var swiper = this;
      if (swiper.enabled) return;
      swiper.enabled = true;

      if (swiper.params.grabCursor) {
        swiper.setGrabCursor();
      }

      swiper.emit('enable');
    };

    _proto.disable = function disable() {
      var swiper = this;
      if (!swiper.enabled) return;
      swiper.enabled = false;

      if (swiper.params.grabCursor) {
        swiper.unsetGrabCursor();
      }

      swiper.emit('disable');
    };

    _proto.setProgress = function setProgress(progress, speed) {
      var swiper = this;
      progress = Math.min(Math.max(progress, 0), 1);
      var min = swiper.minTranslate();
      var max = swiper.maxTranslate();
      var current = (max - min) * progress + min;
      swiper.translateTo(current, typeof speed === 'undefined' ? 0 : speed);
      swiper.updateActiveIndex();
      swiper.updateSlidesClasses();
    };

    _proto.emitContainerClasses = function emitContainerClasses() {
      var swiper = this;
      if (!swiper.params._emitClasses || !swiper.el) return;
      var classes = swiper.el.className.split(' ').filter(function (className) {
        return className.indexOf('swiper-container') === 0 || className.indexOf(swiper.params.containerModifierClass) === 0;
      });
      swiper.emit('_containerClasses', classes.join(' '));
    };

    _proto.getSlideClasses = function getSlideClasses(slideEl) {
      var swiper = this;
      return slideEl.className.split(' ').filter(function (className) {
        return className.indexOf('swiper-slide') === 0 || className.indexOf(swiper.params.slideClass) === 0;
      }).join(' ');
    };

    _proto.emitSlidesClasses = function emitSlidesClasses() {
      var swiper = this;
      if (!swiper.params._emitClasses || !swiper.el) return;
      var updates = [];
      swiper.slides.each(function (slideEl) {
        var classNames = swiper.getSlideClasses(slideEl);
        updates.push({
          slideEl: slideEl,
          classNames: classNames
        });
        swiper.emit('_slideClass', slideEl, classNames);
      });
      swiper.emit('_slideClasses', updates);
    };

    _proto.slidesPerViewDynamic = function slidesPerViewDynamic() {
      var swiper = this;
      var params = swiper.params,
          slides = swiper.slides,
          slidesGrid = swiper.slidesGrid,
          swiperSize = swiper.size,
          activeIndex = swiper.activeIndex;
      var spv = 1;

      if (params.centeredSlides) {
        var slideSize = slides[activeIndex].swiperSlideSize;
        var breakLoop;

        for (var i = activeIndex + 1; i < slides.length; i += 1) {
          if (slides[i] && !breakLoop) {
            slideSize += slides[i].swiperSlideSize;
            spv += 1;
            if (slideSize > swiperSize) breakLoop = true;
          }
        }

        for (var _i = activeIndex - 1; _i >= 0; _i -= 1) {
          if (slides[_i] && !breakLoop) {
            slideSize += slides[_i].swiperSlideSize;
            spv += 1;
            if (slideSize > swiperSize) breakLoop = true;
          }
        }
      } else {
        for (var _i2 = activeIndex + 1; _i2 < slides.length; _i2 += 1) {
          if (slidesGrid[_i2] - slidesGrid[activeIndex] < swiperSize) {
            spv += 1;
          }
        }
      }

      return spv;
    };

    _proto.update = function update() {
      var swiper = this;
      if (!swiper || swiper.destroyed) return;
      var snapGrid = swiper.snapGrid,
          params = swiper.params; // Breakpoints

      if (params.breakpoints) {
        swiper.setBreakpoint();
      }

      swiper.updateSize();
      swiper.updateSlides();
      swiper.updateProgress();
      swiper.updateSlidesClasses();

      function setTranslate() {
        var translateValue = swiper.rtlTranslate ? swiper.translate * -1 : swiper.translate;
        var newTranslate = Math.min(Math.max(translateValue, swiper.maxTranslate()), swiper.minTranslate());
        swiper.setTranslate(newTranslate);
        swiper.updateActiveIndex();
        swiper.updateSlidesClasses();
      }

      var translated;

      if (swiper.params.freeMode) {
        setTranslate();

        if (swiper.params.autoHeight) {
          swiper.updateAutoHeight();
        }
      } else {
        if ((swiper.params.slidesPerView === 'auto' || swiper.params.slidesPerView > 1) && swiper.isEnd && !swiper.params.centeredSlides) {
          translated = swiper.slideTo(swiper.slides.length - 1, 0, false, true);
        } else {
          translated = swiper.slideTo(swiper.activeIndex, 0, false, true);
        }

        if (!translated) {
          setTranslate();
        }
      }

      if (params.watchOverflow && snapGrid !== swiper.snapGrid) {
        swiper.checkOverflow();
      }

      swiper.emit('update');
    };

    _proto.changeDirection = function changeDirection(newDirection, needUpdate) {
      if (needUpdate === void 0) {
        needUpdate = true;
      }

      var swiper = this;
      var currentDirection = swiper.params.direction;

      if (!newDirection) {
        // eslint-disable-next-line
        newDirection = currentDirection === 'horizontal' ? 'vertical' : 'horizontal';
      }

      if (newDirection === currentDirection || newDirection !== 'horizontal' && newDirection !== 'vertical') {
        return swiper;
      }

      swiper.$el.removeClass("" + swiper.params.containerModifierClass + currentDirection).addClass("" + swiper.params.containerModifierClass + newDirection);
      swiper.emitContainerClasses();
      swiper.params.direction = newDirection;
      swiper.slides.each(function (slideEl) {
        if (newDirection === 'vertical') {
          slideEl.style.width = '';
        } else {
          slideEl.style.height = '';
        }
      });
      swiper.emit('changeDirection');
      if (needUpdate) swiper.update();
      return swiper;
    };

    _proto.mount = function mount(el) {
      var swiper = this;
      if (swiper.mounted) return true; // Find el

      var $el = $(el || swiper.params.el);
      el = $el[0];

      if (!el) {
        return false;
      }

      el.swiper = swiper;

      var getWrapperSelector = function getWrapperSelector() {
        return "." + (swiper.params.wrapperClass || '').trim().split(' ').join('.');
      };

      var getWrapper = function getWrapper() {
        if (el && el.shadowRoot && el.shadowRoot.querySelector) {
          var res = $(el.shadowRoot.querySelector(getWrapperSelector())); // Children needs to return slot items

          res.children = function (options) {
            return $el.children(options);
          };

          return res;
        }

        return $el.children(getWrapperSelector());
      }; // Find Wrapper


      var $wrapperEl = getWrapper();

      if ($wrapperEl.length === 0 && swiper.params.createElements) {
        var document = getDocument();
        var wrapper = document.createElement('div');
        $wrapperEl = $(wrapper);
        wrapper.className = swiper.params.wrapperClass;
        $el.append(wrapper);
        $el.children("." + swiper.params.slideClass).each(function (slideEl) {
          $wrapperEl.append(slideEl);
        });
      }

      extend(swiper, {
        $el: $el,
        el: el,
        $wrapperEl: $wrapperEl,
        wrapperEl: $wrapperEl[0],
        mounted: true,
        // RTL
        rtl: el.dir.toLowerCase() === 'rtl' || $el.css('direction') === 'rtl',
        rtlTranslate: swiper.params.direction === 'horizontal' && (el.dir.toLowerCase() === 'rtl' || $el.css('direction') === 'rtl'),
        wrongRTL: $wrapperEl.css('display') === '-webkit-box'
      });
      return true;
    };

    _proto.init = function init(el) {
      var swiper = this;
      if (swiper.initialized) return swiper;
      var mounted = swiper.mount(el);
      if (mounted === false) return swiper;
      swiper.emit('beforeInit'); // Set breakpoint

      if (swiper.params.breakpoints) {
        swiper.setBreakpoint();
      } // Add Classes


      swiper.addClasses(); // Create loop

      if (swiper.params.loop) {
        swiper.loopCreate();
      } // Update size


      swiper.updateSize(); // Update slides

      swiper.updateSlides();

      if (swiper.params.watchOverflow) {
        swiper.checkOverflow();
      } // Set Grab Cursor


      if (swiper.params.grabCursor && swiper.enabled) {
        swiper.setGrabCursor();
      }

      if (swiper.params.preloadImages) {
        swiper.preloadImages();
      } // Slide To Initial Slide


      if (swiper.params.loop) {
        swiper.slideTo(swiper.params.initialSlide + swiper.loopedSlides, 0, swiper.params.runCallbacksOnInit, false, true);
      } else {
        swiper.slideTo(swiper.params.initialSlide, 0, swiper.params.runCallbacksOnInit, false, true);
      } // Attach events


      swiper.attachEvents(); // Init Flag

      swiper.initialized = true; // Emit

      swiper.emit('init');
      swiper.emit('afterInit');
      return swiper;
    };

    _proto.destroy = function destroy(deleteInstance, cleanStyles) {
      if (deleteInstance === void 0) {
        deleteInstance = true;
      }

      if (cleanStyles === void 0) {
        cleanStyles = true;
      }

      var swiper = this;
      var params = swiper.params,
          $el = swiper.$el,
          $wrapperEl = swiper.$wrapperEl,
          slides = swiper.slides;

      if (typeof swiper.params === 'undefined' || swiper.destroyed) {
        return null;
      }

      swiper.emit('beforeDestroy'); // Init Flag

      swiper.initialized = false; // Detach events

      swiper.detachEvents(); // Destroy loop

      if (params.loop) {
        swiper.loopDestroy();
      } // Cleanup styles


      if (cleanStyles) {
        swiper.removeClasses();
        $el.removeAttr('style');
        $wrapperEl.removeAttr('style');

        if (slides && slides.length) {
          slides.removeClass([params.slideVisibleClass, params.slideActiveClass, params.slideNextClass, params.slidePrevClass].join(' ')).removeAttr('style').removeAttr('data-swiper-slide-index');
        }
      }

      swiper.emit('destroy'); // Detach emitter events

      Object.keys(swiper.eventsListeners).forEach(function (eventName) {
        swiper.off(eventName);
      });

      if (deleteInstance !== false) {
        swiper.$el[0].swiper = null;
        deleteProps(swiper);
      }

      swiper.destroyed = true;
      return null;
    };

    Swiper.extendDefaults = function extendDefaults(newDefaults) {
      extend(extendedDefaults, newDefaults);
    };

    Swiper.installModule = function installModule(module) {
      if (!Swiper.prototype.modules) Swiper.prototype.modules = {};
      var name = module.name || Object.keys(Swiper.prototype.modules).length + "_" + now();
      Swiper.prototype.modules[name] = module;
    };

    Swiper.use = function use(module) {
      if (Array.isArray(module)) {
        module.forEach(function (m) {
          return Swiper.installModule(m);
        });
        return Swiper;
      }

      Swiper.installModule(module);
      return Swiper;
    };

    _createClass(Swiper, null, [{
      key: "extendedDefaults",
      get: function get() {
        return extendedDefaults;
      }
    }, {
      key: "defaults",
      get: function get() {
        return defaults;
      }
    }]);

    return Swiper;
  }();

  Object.keys(prototypes).forEach(function (prototypeGroup) {
    Object.keys(prototypes[prototypeGroup]).forEach(function (protoMethod) {
      Swiper.prototype[protoMethod] = prototypes[prototypeGroup][protoMethod];
    });
  });
  Swiper.use([Resize, Observer$1]);
  var Virtual = {
    update: function update(force) {
      var swiper = this;
      var _swiper$params = swiper.params,
          slidesPerView = _swiper$params.slidesPerView,
          slidesPerGroup = _swiper$params.slidesPerGroup,
          centeredSlides = _swiper$params.centeredSlides;
      var _swiper$params$virtua = swiper.params.virtual,
          addSlidesBefore = _swiper$params$virtua.addSlidesBefore,
          addSlidesAfter = _swiper$params$virtua.addSlidesAfter;
      var _swiper$virtual = swiper.virtual,
          previousFrom = _swiper$virtual.from,
          previousTo = _swiper$virtual.to,
          slides = _swiper$virtual.slides,
          previousSlidesGrid = _swiper$virtual.slidesGrid,
          renderSlide = _swiper$virtual.renderSlide,
          previousOffset = _swiper$virtual.offset;
      swiper.updateActiveIndex();
      var activeIndex = swiper.activeIndex || 0;
      var offsetProp;
      if (swiper.rtlTranslate) offsetProp = 'right';else offsetProp = swiper.isHorizontal() ? 'left' : 'top';
      var slidesAfter;
      var slidesBefore;

      if (centeredSlides) {
        slidesAfter = Math.floor(slidesPerView / 2) + slidesPerGroup + addSlidesAfter;
        slidesBefore = Math.floor(slidesPerView / 2) + slidesPerGroup + addSlidesBefore;
      } else {
        slidesAfter = slidesPerView + (slidesPerGroup - 1) + addSlidesAfter;
        slidesBefore = slidesPerGroup + addSlidesBefore;
      }

      var from = Math.max((activeIndex || 0) - slidesBefore, 0);
      var to = Math.min((activeIndex || 0) + slidesAfter, slides.length - 1);
      var offset = (swiper.slidesGrid[from] || 0) - (swiper.slidesGrid[0] || 0);
      extend(swiper.virtual, {
        from: from,
        to: to,
        offset: offset,
        slidesGrid: swiper.slidesGrid
      });

      function onRendered() {
        swiper.updateSlides();
        swiper.updateProgress();
        swiper.updateSlidesClasses();

        if (swiper.lazy && swiper.params.lazy.enabled) {
          swiper.lazy.load();
        }
      }

      if (previousFrom === from && previousTo === to && !force) {
        if (swiper.slidesGrid !== previousSlidesGrid && offset !== previousOffset) {
          swiper.slides.css(offsetProp, offset + "px");
        }

        swiper.updateProgress();
        return;
      }

      if (swiper.params.virtual.renderExternal) {
        swiper.params.virtual.renderExternal.call(swiper, {
          offset: offset,
          from: from,
          to: to,
          slides: function getSlides() {
            var slidesToRender = [];

            for (var i = from; i <= to; i += 1) {
              slidesToRender.push(slides[i]);
            }

            return slidesToRender;
          }()
        });

        if (swiper.params.virtual.renderExternalUpdate) {
          onRendered();
        }

        return;
      }

      var prependIndexes = [];
      var appendIndexes = [];

      if (force) {
        swiper.$wrapperEl.find("." + swiper.params.slideClass).remove();
      } else {
        for (var i = previousFrom; i <= previousTo; i += 1) {
          if (i < from || i > to) {
            swiper.$wrapperEl.find("." + swiper.params.slideClass + "[data-swiper-slide-index=\"" + i + "\"]").remove();
          }
        }
      }

      for (var _i = 0; _i < slides.length; _i += 1) {
        if (_i >= from && _i <= to) {
          if (typeof previousTo === 'undefined' || force) {
            appendIndexes.push(_i);
          } else {
            if (_i > previousTo) appendIndexes.push(_i);
            if (_i < previousFrom) prependIndexes.push(_i);
          }
        }
      }

      appendIndexes.forEach(function (index) {
        swiper.$wrapperEl.append(renderSlide(slides[index], index));
      });
      prependIndexes.sort(function (a, b) {
        return b - a;
      }).forEach(function (index) {
        swiper.$wrapperEl.prepend(renderSlide(slides[index], index));
      });
      swiper.$wrapperEl.children('.swiper-slide').css(offsetProp, offset + "px");
      onRendered();
    },
    renderSlide: function renderSlide(slide, index) {
      var swiper = this;
      var params = swiper.params.virtual;

      if (params.cache && swiper.virtual.cache[index]) {
        return swiper.virtual.cache[index];
      }

      var $slideEl = params.renderSlide ? $(params.renderSlide.call(swiper, slide, index)) : $("<div class=\"" + swiper.params.slideClass + "\" data-swiper-slide-index=\"" + index + "\">" + slide + "</div>");
      if (!$slideEl.attr('data-swiper-slide-index')) $slideEl.attr('data-swiper-slide-index', index);
      if (params.cache) swiper.virtual.cache[index] = $slideEl;
      return $slideEl;
    },
    appendSlide: function appendSlide(slides) {
      var swiper = this;

      if (_typeof(slides) === 'object' && 'length' in slides) {
        for (var i = 0; i < slides.length; i += 1) {
          if (slides[i]) swiper.virtual.slides.push(slides[i]);
        }
      } else {
        swiper.virtual.slides.push(slides);
      }

      swiper.virtual.update(true);
    },
    prependSlide: function prependSlide(slides) {
      var swiper = this;
      var activeIndex = swiper.activeIndex;
      var newActiveIndex = activeIndex + 1;
      var numberOfNewSlides = 1;

      if (Array.isArray(slides)) {
        for (var i = 0; i < slides.length; i += 1) {
          if (slides[i]) swiper.virtual.slides.unshift(slides[i]);
        }

        newActiveIndex = activeIndex + slides.length;
        numberOfNewSlides = slides.length;
      } else {
        swiper.virtual.slides.unshift(slides);
      }

      if (swiper.params.virtual.cache) {
        var cache = swiper.virtual.cache;
        var newCache = {};
        Object.keys(cache).forEach(function (cachedIndex) {
          var $cachedEl = cache[cachedIndex];
          var cachedElIndex = $cachedEl.attr('data-swiper-slide-index');

          if (cachedElIndex) {
            $cachedEl.attr('data-swiper-slide-index', parseInt(cachedElIndex, 10) + 1);
          }

          newCache[parseInt(cachedIndex, 10) + numberOfNewSlides] = $cachedEl;
        });
        swiper.virtual.cache = newCache;
      }

      swiper.virtual.update(true);
      swiper.slideTo(newActiveIndex, 0);
    },
    removeSlide: function removeSlide(slidesIndexes) {
      var swiper = this;
      if (typeof slidesIndexes === 'undefined' || slidesIndexes === null) return;
      var activeIndex = swiper.activeIndex;

      if (Array.isArray(slidesIndexes)) {
        for (var i = slidesIndexes.length - 1; i >= 0; i -= 1) {
          swiper.virtual.slides.splice(slidesIndexes[i], 1);

          if (swiper.params.virtual.cache) {
            delete swiper.virtual.cache[slidesIndexes[i]];
          }

          if (slidesIndexes[i] < activeIndex) activeIndex -= 1;
          activeIndex = Math.max(activeIndex, 0);
        }
      } else {
        swiper.virtual.slides.splice(slidesIndexes, 1);

        if (swiper.params.virtual.cache) {
          delete swiper.virtual.cache[slidesIndexes];
        }

        if (slidesIndexes < activeIndex) activeIndex -= 1;
        activeIndex = Math.max(activeIndex, 0);
      }

      swiper.virtual.update(true);
      swiper.slideTo(activeIndex, 0);
    },
    removeAllSlides: function removeAllSlides() {
      var swiper = this;
      swiper.virtual.slides = [];

      if (swiper.params.virtual.cache) {
        swiper.virtual.cache = {};
      }

      swiper.virtual.update(true);
      swiper.slideTo(0, 0);
    }
  };
  var Virtual$1 = {
    name: 'virtual',
    params: {
      virtual: {
        enabled: false,
        slides: [],
        cache: true,
        renderSlide: null,
        renderExternal: null,
        renderExternalUpdate: true,
        addSlidesBefore: 0,
        addSlidesAfter: 0
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        virtual: _extends({}, Virtual, {
          slides: swiper.params.virtual.slides,
          cache: {}
        })
      });
    },
    on: {
      beforeInit: function beforeInit(swiper) {
        if (!swiper.params.virtual.enabled) return;
        swiper.classNames.push(swiper.params.containerModifierClass + "virtual");
        var overwriteParams = {
          watchSlidesProgress: true
        };
        extend(swiper.params, overwriteParams);
        extend(swiper.originalParams, overwriteParams);

        if (!swiper.params.initialSlide) {
          swiper.virtual.update();
        }
      },
      setTranslate: function setTranslate(swiper) {
        if (!swiper.params.virtual.enabled) return;
        swiper.virtual.update();
      }
    }
  };
  var Keyboard = {
    handle: function handle(event) {
      var swiper = this;
      if (!swiper.enabled) return;
      var window = getWindow();
      var document = getDocument();
      var rtl = swiper.rtlTranslate;
      var e = event;
      if (e.originalEvent) e = e.originalEvent; // jquery fix

      var kc = e.keyCode || e.charCode;
      var pageUpDown = swiper.params.keyboard.pageUpDown;
      var isPageUp = pageUpDown && kc === 33;
      var isPageDown = pageUpDown && kc === 34;
      var isArrowLeft = kc === 37;
      var isArrowRight = kc === 39;
      var isArrowUp = kc === 38;
      var isArrowDown = kc === 40; // Directions locks

      if (!swiper.allowSlideNext && (swiper.isHorizontal() && isArrowRight || swiper.isVertical() && isArrowDown || isPageDown)) {
        return false;
      }

      if (!swiper.allowSlidePrev && (swiper.isHorizontal() && isArrowLeft || swiper.isVertical() && isArrowUp || isPageUp)) {
        return false;
      }

      if (e.shiftKey || e.altKey || e.ctrlKey || e.metaKey) {
        return undefined;
      }

      if (document.activeElement && document.activeElement.nodeName && (document.activeElement.nodeName.toLowerCase() === 'input' || document.activeElement.nodeName.toLowerCase() === 'textarea')) {
        return undefined;
      }

      if (swiper.params.keyboard.onlyInViewport && (isPageUp || isPageDown || isArrowLeft || isArrowRight || isArrowUp || isArrowDown)) {
        var inView = false; // Check that swiper should be inside of visible area of window

        if (swiper.$el.parents("." + swiper.params.slideClass).length > 0 && swiper.$el.parents("." + swiper.params.slideActiveClass).length === 0) {
          return undefined;
        }

        var $el = swiper.$el;
        var swiperWidth = $el[0].clientWidth;
        var swiperHeight = $el[0].clientHeight;
        var windowWidth = window.innerWidth;
        var windowHeight = window.innerHeight;
        var swiperOffset = swiper.$el.offset();
        if (rtl) swiperOffset.left -= swiper.$el[0].scrollLeft;
        var swiperCoord = [[swiperOffset.left, swiperOffset.top], [swiperOffset.left + swiperWidth, swiperOffset.top], [swiperOffset.left, swiperOffset.top + swiperHeight], [swiperOffset.left + swiperWidth, swiperOffset.top + swiperHeight]];

        for (var i = 0; i < swiperCoord.length; i += 1) {
          var point = swiperCoord[i];

          if (point[0] >= 0 && point[0] <= windowWidth && point[1] >= 0 && point[1] <= windowHeight) {
            if (point[0] === 0 && point[1] === 0) continue; // eslint-disable-line

            inView = true;
          }
        }

        if (!inView) return undefined;
      }

      if (swiper.isHorizontal()) {
        if (isPageUp || isPageDown || isArrowLeft || isArrowRight) {
          if (e.preventDefault) e.preventDefault();else e.returnValue = false;
        }

        if ((isPageDown || isArrowRight) && !rtl || (isPageUp || isArrowLeft) && rtl) swiper.slideNext();
        if ((isPageUp || isArrowLeft) && !rtl || (isPageDown || isArrowRight) && rtl) swiper.slidePrev();
      } else {
        if (isPageUp || isPageDown || isArrowUp || isArrowDown) {
          if (e.preventDefault) e.preventDefault();else e.returnValue = false;
        }

        if (isPageDown || isArrowDown) swiper.slideNext();
        if (isPageUp || isArrowUp) swiper.slidePrev();
      }

      swiper.emit('keyPress', kc);
      return undefined;
    },
    enable: function enable() {
      var swiper = this;
      var document = getDocument();
      if (swiper.keyboard.enabled) return;
      $(document).on('keydown', swiper.keyboard.handle);
      swiper.keyboard.enabled = true;
    },
    disable: function disable() {
      var swiper = this;
      var document = getDocument();
      if (!swiper.keyboard.enabled) return;
      $(document).off('keydown', swiper.keyboard.handle);
      swiper.keyboard.enabled = false;
    }
  };
  var Keyboard$1 = {
    name: 'keyboard',
    params: {
      keyboard: {
        enabled: false,
        onlyInViewport: true,
        pageUpDown: true
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        keyboard: _extends({
          enabled: false
        }, Keyboard)
      });
    },
    on: {
      init: function init(swiper) {
        if (swiper.params.keyboard.enabled) {
          swiper.keyboard.enable();
        }
      },
      destroy: function destroy(swiper) {
        if (swiper.keyboard.enabled) {
          swiper.keyboard.disable();
        }
      }
    }
  };
  /* eslint-disable consistent-return */

  function isEventSupported() {
    var document = getDocument();
    var eventName = 'onwheel';
    var isSupported = (eventName in document);

    if (!isSupported) {
      var element = document.createElement('div');
      element.setAttribute(eventName, 'return;');
      isSupported = typeof element[eventName] === 'function';
    }

    if (!isSupported && document.implementation && document.implementation.hasFeature && // always returns true in newer browsers as per the standard.
    // @see http://dom.spec.whatwg.org/#dom-domimplementation-hasfeature
    document.implementation.hasFeature('', '') !== true) {
      // This is the only way to test support for the `wheel` event in IE9+.
      isSupported = document.implementation.hasFeature('Events.wheel', '3.0');
    }

    return isSupported;
  }

  var Mousewheel = {
    lastScrollTime: now(),
    lastEventBeforeSnap: undefined,
    recentWheelEvents: [],
    event: function event() {
      var window = getWindow();
      if (window.navigator.userAgent.indexOf('firefox') > -1) return 'DOMMouseScroll';
      return isEventSupported() ? 'wheel' : 'mousewheel';
    },
    normalize: function normalize(e) {
      // Reasonable defaults
      var PIXEL_STEP = 10;
      var LINE_HEIGHT = 40;
      var PAGE_HEIGHT = 800;
      var sX = 0;
      var sY = 0; // spinX, spinY

      var pX = 0;
      var pY = 0; // pixelX, pixelY
      // Legacy

      if ('detail' in e) {
        sY = e.detail;
      }

      if ('wheelDelta' in e) {
        sY = -e.wheelDelta / 120;
      }

      if ('wheelDeltaY' in e) {
        sY = -e.wheelDeltaY / 120;
      }

      if ('wheelDeltaX' in e) {
        sX = -e.wheelDeltaX / 120;
      } // side scrolling on FF with DOMMouseScroll


      if ('axis' in e && e.axis === e.HORIZONTAL_AXIS) {
        sX = sY;
        sY = 0;
      }

      pX = sX * PIXEL_STEP;
      pY = sY * PIXEL_STEP;

      if ('deltaY' in e) {
        pY = e.deltaY;
      }

      if ('deltaX' in e) {
        pX = e.deltaX;
      }

      if (e.shiftKey && !pX) {
        // if user scrolls with shift he wants horizontal scroll
        pX = pY;
        pY = 0;
      }

      if ((pX || pY) && e.deltaMode) {
        if (e.deltaMode === 1) {
          // delta in LINE units
          pX *= LINE_HEIGHT;
          pY *= LINE_HEIGHT;
        } else {
          // delta in PAGE units
          pX *= PAGE_HEIGHT;
          pY *= PAGE_HEIGHT;
        }
      } // Fall-back if spin cannot be determined


      if (pX && !sX) {
        sX = pX < 1 ? -1 : 1;
      }

      if (pY && !sY) {
        sY = pY < 1 ? -1 : 1;
      }

      return {
        spinX: sX,
        spinY: sY,
        pixelX: pX,
        pixelY: pY
      };
    },
    handleMouseEnter: function handleMouseEnter() {
      var swiper = this;
      if (!swiper.enabled) return;
      swiper.mouseEntered = true;
    },
    handleMouseLeave: function handleMouseLeave() {
      var swiper = this;
      if (!swiper.enabled) return;
      swiper.mouseEntered = false;
    },
    handle: function handle(event) {
      var e = event;
      var disableParentSwiper = true;
      var swiper = this;
      if (!swiper.enabled) return;
      var params = swiper.params.mousewheel;

      if (swiper.params.cssMode) {
        e.preventDefault();
      }

      var target = swiper.$el;

      if (swiper.params.mousewheel.eventsTarget !== 'container') {
        target = $(swiper.params.mousewheel.eventsTarget);
      }

      if (!swiper.mouseEntered && !target[0].contains(e.target) && !params.releaseOnEdges) return true;
      if (e.originalEvent) e = e.originalEvent; // jquery fix

      var delta = 0;
      var rtlFactor = swiper.rtlTranslate ? -1 : 1;
      var data = Mousewheel.normalize(e);

      if (params.forceToAxis) {
        if (swiper.isHorizontal()) {
          if (Math.abs(data.pixelX) > Math.abs(data.pixelY)) delta = -data.pixelX * rtlFactor;else return true;
        } else if (Math.abs(data.pixelY) > Math.abs(data.pixelX)) delta = -data.pixelY;else return true;
      } else {
        delta = Math.abs(data.pixelX) > Math.abs(data.pixelY) ? -data.pixelX * rtlFactor : -data.pixelY;
      }

      if (delta === 0) return true;
      if (params.invert) delta = -delta; // Get the scroll positions

      var positions = swiper.getTranslate() + delta * params.sensitivity;
      if (positions >= swiper.minTranslate()) positions = swiper.minTranslate();
      if (positions <= swiper.maxTranslate()) positions = swiper.maxTranslate(); // When loop is true:
      //     the disableParentSwiper will be true.
      // When loop is false:
      //     if the scroll positions is not on edge,
      //     then the disableParentSwiper will be true.
      //     if the scroll on edge positions,
      //     then the disableParentSwiper will be false.

      disableParentSwiper = swiper.params.loop ? true : !(positions === swiper.minTranslate() || positions === swiper.maxTranslate());
      if (disableParentSwiper && swiper.params.nested) e.stopPropagation();

      if (!swiper.params.freeMode) {
        // Register the new event in a variable which stores the relevant data
        var newEvent = {
          time: now(),
          delta: Math.abs(delta),
          direction: Math.sign(delta),
          raw: event
        }; // Keep the most recent events

        var recentWheelEvents = swiper.mousewheel.recentWheelEvents;

        if (recentWheelEvents.length >= 2) {
          recentWheelEvents.shift(); // only store the last N events
        }

        var prevEvent = recentWheelEvents.length ? recentWheelEvents[recentWheelEvents.length - 1] : undefined;
        recentWheelEvents.push(newEvent); // If there is at least one previous recorded event:
        //   If direction has changed or
        //   if the scroll is quicker than the previous one:
        //     Animate the slider.
        // Else (this is the first time the wheel is moved):
        //     Animate the slider.

        if (prevEvent) {
          if (newEvent.direction !== prevEvent.direction || newEvent.delta > prevEvent.delta || newEvent.time > prevEvent.time + 150) {
            swiper.mousewheel.animateSlider(newEvent);
          }
        } else {
          swiper.mousewheel.animateSlider(newEvent);
        } // If it's time to release the scroll:
        //   Return now so you don't hit the preventDefault.


        if (swiper.mousewheel.releaseScroll(newEvent)) {
          return true;
        }
      } else {
        // Freemode or scrollContainer:
        // If we recently snapped after a momentum scroll, then ignore wheel events
        // to give time for the deceleration to finish. Stop ignoring after 500 msecs
        // or if it's a new scroll (larger delta or inverse sign as last event before
        // an end-of-momentum snap).
        var _newEvent = {
          time: now(),
          delta: Math.abs(delta),
          direction: Math.sign(delta)
        };
        var lastEventBeforeSnap = swiper.mousewheel.lastEventBeforeSnap;
        var ignoreWheelEvents = lastEventBeforeSnap && _newEvent.time < lastEventBeforeSnap.time + 500 && _newEvent.delta <= lastEventBeforeSnap.delta && _newEvent.direction === lastEventBeforeSnap.direction;

        if (!ignoreWheelEvents) {
          swiper.mousewheel.lastEventBeforeSnap = undefined;

          if (swiper.params.loop) {
            swiper.loopFix();
          }

          var position = swiper.getTranslate() + delta * params.sensitivity;
          var wasBeginning = swiper.isBeginning;
          var wasEnd = swiper.isEnd;
          if (position >= swiper.minTranslate()) position = swiper.minTranslate();
          if (position <= swiper.maxTranslate()) position = swiper.maxTranslate();
          swiper.setTransition(0);
          swiper.setTranslate(position);
          swiper.updateProgress();
          swiper.updateActiveIndex();
          swiper.updateSlidesClasses();

          if (!wasBeginning && swiper.isBeginning || !wasEnd && swiper.isEnd) {
            swiper.updateSlidesClasses();
          }

          if (swiper.params.freeModeSticky) {
            // When wheel scrolling starts with sticky (aka snap) enabled, then detect
            // the end of a momentum scroll by storing recent (N=15?) wheel events.
            // 1. do all N events have decreasing or same (absolute value) delta?
            // 2. did all N events arrive in the last M (M=500?) msecs?
            // 3. does the earliest event have an (absolute value) delta that's
            //    at least P (P=1?) larger than the most recent event's delta?
            // 4. does the latest event have a delta that's smaller than Q (Q=6?) pixels?
            // If 1-4 are "yes" then we're near the end of a momentum scroll deceleration.
            // Snap immediately and ignore remaining wheel events in this scroll.
            // See comment above for "remaining wheel events in this scroll" determination.
            // If 1-4 aren't satisfied, then wait to snap until 500ms after the last event.
            clearTimeout(swiper.mousewheel.timeout);
            swiper.mousewheel.timeout = undefined;
            var _recentWheelEvents = swiper.mousewheel.recentWheelEvents;

            if (_recentWheelEvents.length >= 15) {
              _recentWheelEvents.shift(); // only store the last N events

            }

            var _prevEvent = _recentWheelEvents.length ? _recentWheelEvents[_recentWheelEvents.length - 1] : undefined;

            var firstEvent = _recentWheelEvents[0];

            _recentWheelEvents.push(_newEvent);

            if (_prevEvent && (_newEvent.delta > _prevEvent.delta || _newEvent.direction !== _prevEvent.direction)) {
              // Increasing or reverse-sign delta means the user started scrolling again. Clear the wheel event log.
              _recentWheelEvents.splice(0);
            } else if (_recentWheelEvents.length >= 15 && _newEvent.time - firstEvent.time < 500 && firstEvent.delta - _newEvent.delta >= 1 && _newEvent.delta <= 6) {
              // We're at the end of the deceleration of a momentum scroll, so there's no need
              // to wait for more events. Snap ASAP on the next tick.
              // Also, because there's some remaining momentum we'll bias the snap in the
              // direction of the ongoing scroll because it's better UX for the scroll to snap
              // in the same direction as the scroll instead of reversing to snap.  Therefore,
              // if it's already scrolled more than 20% in the current direction, keep going.
              var snapToThreshold = delta > 0 ? 0.8 : 0.2;
              swiper.mousewheel.lastEventBeforeSnap = _newEvent;

              _recentWheelEvents.splice(0);

              swiper.mousewheel.timeout = nextTick(function () {
                swiper.slideToClosest(swiper.params.speed, true, undefined, snapToThreshold);
              }, 0); // no delay; move on next tick
            }

            if (!swiper.mousewheel.timeout) {
              // if we get here, then we haven't detected the end of a momentum scroll, so
              // we'll consider a scroll "complete" when there haven't been any wheel events
              // for 500ms.
              swiper.mousewheel.timeout = nextTick(function () {
                var snapToThreshold = 0.5;
                swiper.mousewheel.lastEventBeforeSnap = _newEvent;

                _recentWheelEvents.splice(0);

                swiper.slideToClosest(swiper.params.speed, true, undefined, snapToThreshold);
              }, 500);
            }
          } // Emit event


          if (!ignoreWheelEvents) swiper.emit('scroll', e); // Stop autoplay

          if (swiper.params.autoplay && swiper.params.autoplayDisableOnInteraction) swiper.autoplay.stop(); // Return page scroll on edge positions

          if (position === swiper.minTranslate() || position === swiper.maxTranslate()) return true;
        }
      }

      if (e.preventDefault) e.preventDefault();else e.returnValue = false;
      return false;
    },
    animateSlider: function animateSlider(newEvent) {
      var swiper = this;
      var window = getWindow();

      if (this.params.mousewheel.thresholdDelta && newEvent.delta < this.params.mousewheel.thresholdDelta) {
        // Prevent if delta of wheel scroll delta is below configured threshold
        return false;
      }

      if (this.params.mousewheel.thresholdTime && now() - swiper.mousewheel.lastScrollTime < this.params.mousewheel.thresholdTime) {
        // Prevent if time between scrolls is below configured threshold
        return false;
      } // If the movement is NOT big enough and
      // if the last time the user scrolled was too close to the current one (avoid continuously triggering the slider):
      //   Don't go any further (avoid insignificant scroll movement).


      if (newEvent.delta >= 6 && now() - swiper.mousewheel.lastScrollTime < 60) {
        // Return false as a default
        return true;
      } // If user is scrolling towards the end:
      //   If the slider hasn't hit the latest slide or
      //   if the slider is a loop and
      //   if the slider isn't moving right now:
      //     Go to next slide and
      //     emit a scroll event.
      // Else (the user is scrolling towards the beginning) and
      // if the slider hasn't hit the first slide or
      // if the slider is a loop and
      // if the slider isn't moving right now:
      //   Go to prev slide and
      //   emit a scroll event.


      if (newEvent.direction < 0) {
        if ((!swiper.isEnd || swiper.params.loop) && !swiper.animating) {
          swiper.slideNext();
          swiper.emit('scroll', newEvent.raw);
        }
      } else if ((!swiper.isBeginning || swiper.params.loop) && !swiper.animating) {
        swiper.slidePrev();
        swiper.emit('scroll', newEvent.raw);
      } // If you got here is because an animation has been triggered so store the current time


      swiper.mousewheel.lastScrollTime = new window.Date().getTime(); // Return false as a default

      return false;
    },
    releaseScroll: function releaseScroll(newEvent) {
      var swiper = this;
      var params = swiper.params.mousewheel;

      if (newEvent.direction < 0) {
        if (swiper.isEnd && !swiper.params.loop && params.releaseOnEdges) {
          // Return true to animate scroll on edges
          return true;
        }
      } else if (swiper.isBeginning && !swiper.params.loop && params.releaseOnEdges) {
        // Return true to animate scroll on edges
        return true;
      }

      return false;
    },
    enable: function enable() {
      var swiper = this;
      var event = Mousewheel.event();

      if (swiper.params.cssMode) {
        swiper.wrapperEl.removeEventListener(event, swiper.mousewheel.handle);
        return true;
      }

      if (!event) return false;
      if (swiper.mousewheel.enabled) return false;
      var target = swiper.$el;

      if (swiper.params.mousewheel.eventsTarget !== 'container') {
        target = $(swiper.params.mousewheel.eventsTarget);
      }

      target.on('mouseenter', swiper.mousewheel.handleMouseEnter);
      target.on('mouseleave', swiper.mousewheel.handleMouseLeave);
      target.on(event, swiper.mousewheel.handle);
      swiper.mousewheel.enabled = true;
      return true;
    },
    disable: function disable() {
      var swiper = this;
      var event = Mousewheel.event();

      if (swiper.params.cssMode) {
        swiper.wrapperEl.addEventListener(event, swiper.mousewheel.handle);
        return true;
      }

      if (!event) return false;
      if (!swiper.mousewheel.enabled) return false;
      var target = swiper.$el;

      if (swiper.params.mousewheel.eventsTarget !== 'container') {
        target = $(swiper.params.mousewheel.eventsTarget);
      }

      target.off(event, swiper.mousewheel.handle);
      swiper.mousewheel.enabled = false;
      return true;
    }
  };
  var Mousewheel$1 = {
    name: 'mousewheel',
    params: {
      mousewheel: {
        enabled: false,
        releaseOnEdges: false,
        invert: false,
        forceToAxis: false,
        sensitivity: 1,
        eventsTarget: 'container',
        thresholdDelta: null,
        thresholdTime: null
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        mousewheel: {
          enabled: false,
          lastScrollTime: now(),
          lastEventBeforeSnap: undefined,
          recentWheelEvents: [],
          enable: Mousewheel.enable,
          disable: Mousewheel.disable,
          handle: Mousewheel.handle,
          handleMouseEnter: Mousewheel.handleMouseEnter,
          handleMouseLeave: Mousewheel.handleMouseLeave,
          animateSlider: Mousewheel.animateSlider,
          releaseScroll: Mousewheel.releaseScroll
        }
      });
    },
    on: {
      init: function init(swiper) {
        if (!swiper.params.mousewheel.enabled && swiper.params.cssMode) {
          swiper.mousewheel.disable();
        }

        if (swiper.params.mousewheel.enabled) swiper.mousewheel.enable();
      },
      destroy: function destroy(swiper) {
        if (swiper.params.cssMode) {
          swiper.mousewheel.enable();
        }

        if (swiper.mousewheel.enabled) swiper.mousewheel.disable();
      }
    }
  };
  var Navigation = {
    toggleEl: function toggleEl($el, disabled) {
      $el[disabled ? 'addClass' : 'removeClass'](this.params.navigation.disabledClass);
      if ($el[0] && $el[0].tagName === 'BUTTON') $el[0].disabled = disabled;
    },
    update: function update() {
      // Update Navigation Buttons
      var swiper = this;
      var params = swiper.params.navigation;
      var toggleEl = swiper.navigation.toggleEl;
      if (swiper.params.loop) return;
      var _swiper$navigation = swiper.navigation,
          $nextEl = _swiper$navigation.$nextEl,
          $prevEl = _swiper$navigation.$prevEl;

      if ($prevEl && $prevEl.length > 0) {
        if (swiper.isBeginning) {
          toggleEl($prevEl, true);
        } else {
          toggleEl($prevEl, false);
        }

        if (swiper.params.watchOverflow && swiper.enabled) {
          $prevEl[swiper.isLocked ? 'addClass' : 'removeClass'](params.lockClass);
        }
      }

      if ($nextEl && $nextEl.length > 0) {
        if (swiper.isEnd) {
          toggleEl($nextEl, true);
        } else {
          toggleEl($nextEl, false);
        }

        if (swiper.params.watchOverflow && swiper.enabled) {
          $nextEl[swiper.isLocked ? 'addClass' : 'removeClass'](params.lockClass);
        }
      }
    },
    onPrevClick: function onPrevClick(e) {
      var swiper = this;
      e.preventDefault();
      if (swiper.isBeginning && !swiper.params.loop) return;
      swiper.slidePrev();
    },
    onNextClick: function onNextClick(e) {
      var swiper = this;
      e.preventDefault();
      if (swiper.isEnd && !swiper.params.loop) return;
      swiper.slideNext();
    },
    init: function init() {
      var swiper = this;
      var params = swiper.params.navigation;
      swiper.params.navigation = createElementIfNotDefined(swiper.$el, swiper.params.navigation, swiper.params.createElements, {
        nextEl: 'swiper-button-next',
        prevEl: 'swiper-button-prev'
      });
      if (!(params.nextEl || params.prevEl)) return;
      var $nextEl;
      var $prevEl;

      if (params.nextEl) {
        $nextEl = $(params.nextEl);

        if (swiper.params.uniqueNavElements && typeof params.nextEl === 'string' && $nextEl.length > 1 && swiper.$el.find(params.nextEl).length === 1) {
          $nextEl = swiper.$el.find(params.nextEl);
        }
      }

      if (params.prevEl) {
        $prevEl = $(params.prevEl);

        if (swiper.params.uniqueNavElements && typeof params.prevEl === 'string' && $prevEl.length > 1 && swiper.$el.find(params.prevEl).length === 1) {
          $prevEl = swiper.$el.find(params.prevEl);
        }
      }

      if ($nextEl && $nextEl.length > 0) {
        $nextEl.on('click', swiper.navigation.onNextClick);
      }

      if ($prevEl && $prevEl.length > 0) {
        $prevEl.on('click', swiper.navigation.onPrevClick);
      }

      extend(swiper.navigation, {
        $nextEl: $nextEl,
        nextEl: $nextEl && $nextEl[0],
        $prevEl: $prevEl,
        prevEl: $prevEl && $prevEl[0]
      });

      if (!swiper.enabled) {
        if ($nextEl) $nextEl.addClass(params.lockClass);
        if ($prevEl) $prevEl.addClass(params.lockClass);
      }
    },
    destroy: function destroy() {
      var swiper = this;
      var _swiper$navigation2 = swiper.navigation,
          $nextEl = _swiper$navigation2.$nextEl,
          $prevEl = _swiper$navigation2.$prevEl;

      if ($nextEl && $nextEl.length) {
        $nextEl.off('click', swiper.navigation.onNextClick);
        $nextEl.removeClass(swiper.params.navigation.disabledClass);
      }

      if ($prevEl && $prevEl.length) {
        $prevEl.off('click', swiper.navigation.onPrevClick);
        $prevEl.removeClass(swiper.params.navigation.disabledClass);
      }
    }
  };
  var Navigation$1 = {
    name: 'navigation',
    params: {
      navigation: {
        nextEl: null,
        prevEl: null,
        hideOnClick: false,
        disabledClass: 'swiper-button-disabled',
        hiddenClass: 'swiper-button-hidden',
        lockClass: 'swiper-button-lock'
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        navigation: _extends({}, Navigation)
      });
    },
    on: {
      init: function init(swiper) {
        swiper.navigation.init();
        swiper.navigation.update();
      },
      toEdge: function toEdge(swiper) {
        swiper.navigation.update();
      },
      fromEdge: function fromEdge(swiper) {
        swiper.navigation.update();
      },
      destroy: function destroy(swiper) {
        swiper.navigation.destroy();
      },
      'enable disable': function enableDisable(swiper) {
        var _swiper$navigation3 = swiper.navigation,
            $nextEl = _swiper$navigation3.$nextEl,
            $prevEl = _swiper$navigation3.$prevEl;

        if ($nextEl) {
          $nextEl[swiper.enabled ? 'removeClass' : 'addClass'](swiper.params.navigation.lockClass);
        }

        if ($prevEl) {
          $prevEl[swiper.enabled ? 'removeClass' : 'addClass'](swiper.params.navigation.lockClass);
        }
      },
      click: function click(swiper, e) {
        var _swiper$navigation4 = swiper.navigation,
            $nextEl = _swiper$navigation4.$nextEl,
            $prevEl = _swiper$navigation4.$prevEl;
        var targetEl = e.target;

        if (swiper.params.navigation.hideOnClick && !$(targetEl).is($prevEl) && !$(targetEl).is($nextEl)) {
          if (swiper.pagination && swiper.params.pagination && swiper.params.pagination.clickable && (swiper.pagination.el === targetEl || swiper.pagination.el.contains(targetEl))) return;
          var isHidden;

          if ($nextEl) {
            isHidden = $nextEl.hasClass(swiper.params.navigation.hiddenClass);
          } else if ($prevEl) {
            isHidden = $prevEl.hasClass(swiper.params.navigation.hiddenClass);
          }

          if (isHidden === true) {
            swiper.emit('navigationShow');
          } else {
            swiper.emit('navigationHide');
          }

          if ($nextEl) {
            $nextEl.toggleClass(swiper.params.navigation.hiddenClass);
          }

          if ($prevEl) {
            $prevEl.toggleClass(swiper.params.navigation.hiddenClass);
          }
        }
      }
    }
  };
  var Pagination = {
    update: function update() {
      // Render || Update Pagination bullets/items
      var swiper = this;
      var rtl = swiper.rtl;
      var params = swiper.params.pagination;
      if (!params.el || !swiper.pagination.el || !swiper.pagination.$el || swiper.pagination.$el.length === 0) return;
      var slidesLength = swiper.virtual && swiper.params.virtual.enabled ? swiper.virtual.slides.length : swiper.slides.length;
      var $el = swiper.pagination.$el; // Current/Total

      var current;
      var total = swiper.params.loop ? Math.ceil((slidesLength - swiper.loopedSlides * 2) / swiper.params.slidesPerGroup) : swiper.snapGrid.length;

      if (swiper.params.loop) {
        current = Math.ceil((swiper.activeIndex - swiper.loopedSlides) / swiper.params.slidesPerGroup);

        if (current > slidesLength - 1 - swiper.loopedSlides * 2) {
          current -= slidesLength - swiper.loopedSlides * 2;
        }

        if (current > total - 1) current -= total;
        if (current < 0 && swiper.params.paginationType !== 'bullets') current = total + current;
      } else if (typeof swiper.snapIndex !== 'undefined') {
        current = swiper.snapIndex;
      } else {
        current = swiper.activeIndex || 0;
      } // Types


      if (params.type === 'bullets' && swiper.pagination.bullets && swiper.pagination.bullets.length > 0) {
        var bullets = swiper.pagination.bullets;
        var firstIndex;
        var lastIndex;
        var midIndex;

        if (params.dynamicBullets) {
          swiper.pagination.bulletSize = bullets.eq(0)[swiper.isHorizontal() ? 'outerWidth' : 'outerHeight'](true);
          $el.css(swiper.isHorizontal() ? 'width' : 'height', swiper.pagination.bulletSize * (params.dynamicMainBullets + 4) + "px");

          if (params.dynamicMainBullets > 1 && swiper.previousIndex !== undefined) {
            swiper.pagination.dynamicBulletIndex += current - swiper.previousIndex;

            if (swiper.pagination.dynamicBulletIndex > params.dynamicMainBullets - 1) {
              swiper.pagination.dynamicBulletIndex = params.dynamicMainBullets - 1;
            } else if (swiper.pagination.dynamicBulletIndex < 0) {
              swiper.pagination.dynamicBulletIndex = 0;
            }
          }

          firstIndex = current - swiper.pagination.dynamicBulletIndex;
          lastIndex = firstIndex + (Math.min(bullets.length, params.dynamicMainBullets) - 1);
          midIndex = (lastIndex + firstIndex) / 2;
        }

        bullets.removeClass(params.bulletActiveClass + " " + params.bulletActiveClass + "-next " + params.bulletActiveClass + "-next-next " + params.bulletActiveClass + "-prev " + params.bulletActiveClass + "-prev-prev " + params.bulletActiveClass + "-main");

        if ($el.length > 1) {
          bullets.each(function (bullet) {
            var $bullet = $(bullet);
            var bulletIndex = $bullet.index();

            if (bulletIndex === current) {
              $bullet.addClass(params.bulletActiveClass);
            }

            if (params.dynamicBullets) {
              if (bulletIndex >= firstIndex && bulletIndex <= lastIndex) {
                $bullet.addClass(params.bulletActiveClass + "-main");
              }

              if (bulletIndex === firstIndex) {
                $bullet.prev().addClass(params.bulletActiveClass + "-prev").prev().addClass(params.bulletActiveClass + "-prev-prev");
              }

              if (bulletIndex === lastIndex) {
                $bullet.next().addClass(params.bulletActiveClass + "-next").next().addClass(params.bulletActiveClass + "-next-next");
              }
            }
          });
        } else {
          var $bullet = bullets.eq(current);
          var bulletIndex = $bullet.index();
          $bullet.addClass(params.bulletActiveClass);

          if (params.dynamicBullets) {
            var $firstDisplayedBullet = bullets.eq(firstIndex);
            var $lastDisplayedBullet = bullets.eq(lastIndex);

            for (var i = firstIndex; i <= lastIndex; i += 1) {
              bullets.eq(i).addClass(params.bulletActiveClass + "-main");
            }

            if (swiper.params.loop) {
              if (bulletIndex >= bullets.length - params.dynamicMainBullets) {
                for (var _i = params.dynamicMainBullets; _i >= 0; _i -= 1) {
                  bullets.eq(bullets.length - _i).addClass(params.bulletActiveClass + "-main");
                }

                bullets.eq(bullets.length - params.dynamicMainBullets - 1).addClass(params.bulletActiveClass + "-prev");
              } else {
                $firstDisplayedBullet.prev().addClass(params.bulletActiveClass + "-prev").prev().addClass(params.bulletActiveClass + "-prev-prev");
                $lastDisplayedBullet.next().addClass(params.bulletActiveClass + "-next").next().addClass(params.bulletActiveClass + "-next-next");
              }
            } else {
              $firstDisplayedBullet.prev().addClass(params.bulletActiveClass + "-prev").prev().addClass(params.bulletActiveClass + "-prev-prev");
              $lastDisplayedBullet.next().addClass(params.bulletActiveClass + "-next").next().addClass(params.bulletActiveClass + "-next-next");
            }
          }
        }

        if (params.dynamicBullets) {
          var dynamicBulletsLength = Math.min(bullets.length, params.dynamicMainBullets + 4);
          var bulletsOffset = (swiper.pagination.bulletSize * dynamicBulletsLength - swiper.pagination.bulletSize) / 2 - midIndex * swiper.pagination.bulletSize;
          var offsetProp = rtl ? 'right' : 'left';
          bullets.css(swiper.isHorizontal() ? offsetProp : 'top', bulletsOffset + "px");
        }
      }

      if (params.type === 'fraction') {
        $el.find(classesToSelector(params.currentClass)).text(params.formatFractionCurrent(current + 1));
        $el.find(classesToSelector(params.totalClass)).text(params.formatFractionTotal(total));
      }

      if (params.type === 'progressbar') {
        var progressbarDirection;

        if (params.progressbarOpposite) {
          progressbarDirection = swiper.isHorizontal() ? 'vertical' : 'horizontal';
        } else {
          progressbarDirection = swiper.isHorizontal() ? 'horizontal' : 'vertical';
        }

        var scale = (current + 1) / total;
        var scaleX = 1;
        var scaleY = 1;

        if (progressbarDirection === 'horizontal') {
          scaleX = scale;
        } else {
          scaleY = scale;
        }

        $el.find(classesToSelector(params.progressbarFillClass)).transform("translate3d(0,0,0) scaleX(" + scaleX + ") scaleY(" + scaleY + ")").transition(swiper.params.speed);
      }

      if (params.type === 'custom' && params.renderCustom) {
        $el.html(params.renderCustom(swiper, current + 1, total));
        swiper.emit('paginationRender', $el[0]);
      } else {
        swiper.emit('paginationUpdate', $el[0]);
      }

      if (swiper.params.watchOverflow && swiper.enabled) {
        $el[swiper.isLocked ? 'addClass' : 'removeClass'](params.lockClass);
      }
    },
    render: function render() {
      // Render Container
      var swiper = this;
      var params = swiper.params.pagination;
      if (!params.el || !swiper.pagination.el || !swiper.pagination.$el || swiper.pagination.$el.length === 0) return;
      var slidesLength = swiper.virtual && swiper.params.virtual.enabled ? swiper.virtual.slides.length : swiper.slides.length;
      var $el = swiper.pagination.$el;
      var paginationHTML = '';

      if (params.type === 'bullets') {
        var numberOfBullets = swiper.params.loop ? Math.ceil((slidesLength - swiper.loopedSlides * 2) / swiper.params.slidesPerGroup) : swiper.snapGrid.length;

        if (swiper.params.freeMode && !swiper.params.loop && numberOfBullets > slidesLength) {
          numberOfBullets = slidesLength;
        }

        for (var i = 0; i < numberOfBullets; i += 1) {
          if (params.renderBullet) {
            paginationHTML += params.renderBullet.call(swiper, i, params.bulletClass);
          } else {
            paginationHTML += "<" + params.bulletElement + " class=\"" + params.bulletClass + "\"></" + params.bulletElement + ">";
          }
        }

        $el.html(paginationHTML);
        swiper.pagination.bullets = $el.find(classesToSelector(params.bulletClass));
      }

      if (params.type === 'fraction') {
        if (params.renderFraction) {
          paginationHTML = params.renderFraction.call(swiper, params.currentClass, params.totalClass);
        } else {
          paginationHTML = "<span class=\"" + params.currentClass + "\"></span>" + ' / ' + ("<span class=\"" + params.totalClass + "\"></span>");
        }

        $el.html(paginationHTML);
      }

      if (params.type === 'progressbar') {
        if (params.renderProgressbar) {
          paginationHTML = params.renderProgressbar.call(swiper, params.progressbarFillClass);
        } else {
          paginationHTML = "<span class=\"" + params.progressbarFillClass + "\"></span>";
        }

        $el.html(paginationHTML);
      }

      if (params.type !== 'custom') {
        swiper.emit('paginationRender', swiper.pagination.$el[0]);
      }
    },
    init: function init() {
      var swiper = this;
      swiper.params.pagination = createElementIfNotDefined(swiper.$el, swiper.params.pagination, swiper.params.createElements, {
        el: 'swiper-pagination'
      });
      var params = swiper.params.pagination;
      if (!params.el) return;
      var $el = $(params.el);
      if ($el.length === 0) return;

      if (swiper.params.uniqueNavElements && typeof params.el === 'string' && $el.length > 1) {
        $el = swiper.$el.find(params.el);
      }

      if (params.type === 'bullets' && params.clickable) {
        $el.addClass(params.clickableClass);
      }

      $el.addClass(params.modifierClass + params.type);

      if (params.type === 'bullets' && params.dynamicBullets) {
        $el.addClass("" + params.modifierClass + params.type + "-dynamic");
        swiper.pagination.dynamicBulletIndex = 0;

        if (params.dynamicMainBullets < 1) {
          params.dynamicMainBullets = 1;
        }
      }

      if (params.type === 'progressbar' && params.progressbarOpposite) {
        $el.addClass(params.progressbarOppositeClass);
      }

      if (params.clickable) {
        $el.on('click', classesToSelector(params.bulletClass), function onClick(e) {
          e.preventDefault();
          var index = $(this).index() * swiper.params.slidesPerGroup;
          if (swiper.params.loop) index += swiper.loopedSlides;
          swiper.slideTo(index);
        });
      }

      extend(swiper.pagination, {
        $el: $el,
        el: $el[0]
      });

      if (!swiper.enabled) {
        $el.addClass(params.lockClass);
      }
    },
    destroy: function destroy() {
      var swiper = this;
      var params = swiper.params.pagination;
      if (!params.el || !swiper.pagination.el || !swiper.pagination.$el || swiper.pagination.$el.length === 0) return;
      var $el = swiper.pagination.$el;
      $el.removeClass(params.hiddenClass);
      $el.removeClass(params.modifierClass + params.type);
      if (swiper.pagination.bullets) swiper.pagination.bullets.removeClass(params.bulletActiveClass);

      if (params.clickable) {
        $el.off('click', classesToSelector(params.bulletClass));
      }
    }
  };
  var Pagination$1 = {
    name: 'pagination',
    params: {
      pagination: {
        el: null,
        bulletElement: 'span',
        clickable: false,
        hideOnClick: false,
        renderBullet: null,
        renderProgressbar: null,
        renderFraction: null,
        renderCustom: null,
        progressbarOpposite: false,
        type: 'bullets',
        // 'bullets' or 'progressbar' or 'fraction' or 'custom'
        dynamicBullets: false,
        dynamicMainBullets: 1,
        formatFractionCurrent: function formatFractionCurrent(number) {
          return number;
        },
        formatFractionTotal: function formatFractionTotal(number) {
          return number;
        },
        bulletClass: 'swiper-pagination-bullet',
        bulletActiveClass: 'swiper-pagination-bullet-active',
        modifierClass: 'swiper-pagination-',
        // NEW
        currentClass: 'swiper-pagination-current',
        totalClass: 'swiper-pagination-total',
        hiddenClass: 'swiper-pagination-hidden',
        progressbarFillClass: 'swiper-pagination-progressbar-fill',
        progressbarOppositeClass: 'swiper-pagination-progressbar-opposite',
        clickableClass: 'swiper-pagination-clickable',
        // NEW
        lockClass: 'swiper-pagination-lock'
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        pagination: _extends({
          dynamicBulletIndex: 0
        }, Pagination)
      });
    },
    on: {
      init: function init(swiper) {
        swiper.pagination.init();
        swiper.pagination.render();
        swiper.pagination.update();
      },
      activeIndexChange: function activeIndexChange(swiper) {
        if (swiper.params.loop) {
          swiper.pagination.update();
        } else if (typeof swiper.snapIndex === 'undefined') {
          swiper.pagination.update();
        }
      },
      snapIndexChange: function snapIndexChange(swiper) {
        if (!swiper.params.loop) {
          swiper.pagination.update();
        }
      },
      slidesLengthChange: function slidesLengthChange(swiper) {
        if (swiper.params.loop) {
          swiper.pagination.render();
          swiper.pagination.update();
        }
      },
      snapGridLengthChange: function snapGridLengthChange(swiper) {
        if (!swiper.params.loop) {
          swiper.pagination.render();
          swiper.pagination.update();
        }
      },
      destroy: function destroy(swiper) {
        swiper.pagination.destroy();
      },
      'enable disable': function enableDisable(swiper) {
        var $el = swiper.pagination.$el;

        if ($el) {
          $el[swiper.enabled ? 'removeClass' : 'addClass'](swiper.params.pagination.lockClass);
        }
      },
      click: function click(swiper, e) {
        var targetEl = e.target;

        if (swiper.params.pagination.el && swiper.params.pagination.hideOnClick && swiper.pagination.$el.length > 0 && !$(targetEl).hasClass(swiper.params.pagination.bulletClass)) {
          if (swiper.navigation && (swiper.navigation.nextEl && targetEl === swiper.navigation.nextEl || swiper.navigation.prevEl && targetEl === swiper.navigation.prevEl)) return;
          var isHidden = swiper.pagination.$el.hasClass(swiper.params.pagination.hiddenClass);

          if (isHidden === true) {
            swiper.emit('paginationShow');
          } else {
            swiper.emit('paginationHide');
          }

          swiper.pagination.$el.toggleClass(swiper.params.pagination.hiddenClass);
        }
      }
    }
  };
  var Scrollbar = {
    setTranslate: function setTranslate() {
      var swiper = this;
      if (!swiper.params.scrollbar.el || !swiper.scrollbar.el) return;
      var scrollbar = swiper.scrollbar,
          rtl = swiper.rtlTranslate,
          progress = swiper.progress;
      var dragSize = scrollbar.dragSize,
          trackSize = scrollbar.trackSize,
          $dragEl = scrollbar.$dragEl,
          $el = scrollbar.$el;
      var params = swiper.params.scrollbar;
      var newSize = dragSize;
      var newPos = (trackSize - dragSize) * progress;

      if (rtl) {
        newPos = -newPos;

        if (newPos > 0) {
          newSize = dragSize - newPos;
          newPos = 0;
        } else if (-newPos + dragSize > trackSize) {
          newSize = trackSize + newPos;
        }
      } else if (newPos < 0) {
        newSize = dragSize + newPos;
        newPos = 0;
      } else if (newPos + dragSize > trackSize) {
        newSize = trackSize - newPos;
      }

      if (swiper.isHorizontal()) {
        $dragEl.transform("translate3d(" + newPos + "px, 0, 0)");
        $dragEl[0].style.width = newSize + "px";
      } else {
        $dragEl.transform("translate3d(0px, " + newPos + "px, 0)");
        $dragEl[0].style.height = newSize + "px";
      }

      if (params.hide) {
        clearTimeout(swiper.scrollbar.timeout);
        $el[0].style.opacity = 1;
        swiper.scrollbar.timeout = setTimeout(function () {
          $el[0].style.opacity = 0;
          $el.transition(400);
        }, 1000);
      }
    },
    setTransition: function setTransition(duration) {
      var swiper = this;
      if (!swiper.params.scrollbar.el || !swiper.scrollbar.el) return;
      swiper.scrollbar.$dragEl.transition(duration);
    },
    updateSize: function updateSize() {
      var swiper = this;
      if (!swiper.params.scrollbar.el || !swiper.scrollbar.el) return;
      var scrollbar = swiper.scrollbar;
      var $dragEl = scrollbar.$dragEl,
          $el = scrollbar.$el;
      $dragEl[0].style.width = '';
      $dragEl[0].style.height = '';
      var trackSize = swiper.isHorizontal() ? $el[0].offsetWidth : $el[0].offsetHeight;
      var divider = swiper.size / swiper.virtualSize;
      var moveDivider = divider * (trackSize / swiper.size);
      var dragSize;

      if (swiper.params.scrollbar.dragSize === 'auto') {
        dragSize = trackSize * divider;
      } else {
        dragSize = parseInt(swiper.params.scrollbar.dragSize, 10);
      }

      if (swiper.isHorizontal()) {
        $dragEl[0].style.width = dragSize + "px";
      } else {
        $dragEl[0].style.height = dragSize + "px";
      }

      if (divider >= 1) {
        $el[0].style.display = 'none';
      } else {
        $el[0].style.display = '';
      }

      if (swiper.params.scrollbar.hide) {
        $el[0].style.opacity = 0;
      }

      extend(scrollbar, {
        trackSize: trackSize,
        divider: divider,
        moveDivider: moveDivider,
        dragSize: dragSize
      });

      if (swiper.params.watchOverflow && swiper.enabled) {
        scrollbar.$el[swiper.isLocked ? 'addClass' : 'removeClass'](swiper.params.scrollbar.lockClass);
      }
    },
    getPointerPosition: function getPointerPosition(e) {
      var swiper = this;

      if (swiper.isHorizontal()) {
        return e.type === 'touchstart' || e.type === 'touchmove' ? e.targetTouches[0].clientX : e.clientX;
      }

      return e.type === 'touchstart' || e.type === 'touchmove' ? e.targetTouches[0].clientY : e.clientY;
    },
    setDragPosition: function setDragPosition(e) {
      var swiper = this;
      var scrollbar = swiper.scrollbar,
          rtl = swiper.rtlTranslate;
      var $el = scrollbar.$el,
          dragSize = scrollbar.dragSize,
          trackSize = scrollbar.trackSize,
          dragStartPos = scrollbar.dragStartPos;
      var positionRatio;
      positionRatio = (scrollbar.getPointerPosition(e) - $el.offset()[swiper.isHorizontal() ? 'left' : 'top'] - (dragStartPos !== null ? dragStartPos : dragSize / 2)) / (trackSize - dragSize);
      positionRatio = Math.max(Math.min(positionRatio, 1), 0);

      if (rtl) {
        positionRatio = 1 - positionRatio;
      }

      var position = swiper.minTranslate() + (swiper.maxTranslate() - swiper.minTranslate()) * positionRatio;
      swiper.updateProgress(position);
      swiper.setTranslate(position);
      swiper.updateActiveIndex();
      swiper.updateSlidesClasses();
    },
    onDragStart: function onDragStart(e) {
      var swiper = this;
      var params = swiper.params.scrollbar;
      var scrollbar = swiper.scrollbar,
          $wrapperEl = swiper.$wrapperEl;
      var $el = scrollbar.$el,
          $dragEl = scrollbar.$dragEl;
      swiper.scrollbar.isTouched = true;
      swiper.scrollbar.dragStartPos = e.target === $dragEl[0] || e.target === $dragEl ? scrollbar.getPointerPosition(e) - e.target.getBoundingClientRect()[swiper.isHorizontal() ? 'left' : 'top'] : null;
      e.preventDefault();
      e.stopPropagation();
      $wrapperEl.transition(100);
      $dragEl.transition(100);
      scrollbar.setDragPosition(e);
      clearTimeout(swiper.scrollbar.dragTimeout);
      $el.transition(0);

      if (params.hide) {
        $el.css('opacity', 1);
      }

      if (swiper.params.cssMode) {
        swiper.$wrapperEl.css('scroll-snap-type', 'none');
      }

      swiper.emit('scrollbarDragStart', e);
    },
    onDragMove: function onDragMove(e) {
      var swiper = this;
      var scrollbar = swiper.scrollbar,
          $wrapperEl = swiper.$wrapperEl;
      var $el = scrollbar.$el,
          $dragEl = scrollbar.$dragEl;
      if (!swiper.scrollbar.isTouched) return;
      if (e.preventDefault) e.preventDefault();else e.returnValue = false;
      scrollbar.setDragPosition(e);
      $wrapperEl.transition(0);
      $el.transition(0);
      $dragEl.transition(0);
      swiper.emit('scrollbarDragMove', e);
    },
    onDragEnd: function onDragEnd(e) {
      var swiper = this;
      var params = swiper.params.scrollbar;
      var scrollbar = swiper.scrollbar,
          $wrapperEl = swiper.$wrapperEl;
      var $el = scrollbar.$el;
      if (!swiper.scrollbar.isTouched) return;
      swiper.scrollbar.isTouched = false;

      if (swiper.params.cssMode) {
        swiper.$wrapperEl.css('scroll-snap-type', '');
        $wrapperEl.transition('');
      }

      if (params.hide) {
        clearTimeout(swiper.scrollbar.dragTimeout);
        swiper.scrollbar.dragTimeout = nextTick(function () {
          $el.css('opacity', 0);
          $el.transition(400);
        }, 1000);
      }

      swiper.emit('scrollbarDragEnd', e);

      if (params.snapOnRelease) {
        swiper.slideToClosest();
      }
    },
    enableDraggable: function enableDraggable() {
      var swiper = this;
      if (!swiper.params.scrollbar.el) return;
      var document = getDocument();
      var scrollbar = swiper.scrollbar,
          touchEventsTouch = swiper.touchEventsTouch,
          touchEventsDesktop = swiper.touchEventsDesktop,
          params = swiper.params,
          support = swiper.support;
      var $el = scrollbar.$el;
      var target = $el[0];
      var activeListener = support.passiveListener && params.passiveListeners ? {
        passive: false,
        capture: false
      } : false;
      var passiveListener = support.passiveListener && params.passiveListeners ? {
        passive: true,
        capture: false
      } : false;
      if (!target) return;

      if (!support.touch) {
        target.addEventListener(touchEventsDesktop.start, swiper.scrollbar.onDragStart, activeListener);
        document.addEventListener(touchEventsDesktop.move, swiper.scrollbar.onDragMove, activeListener);
        document.addEventListener(touchEventsDesktop.end, swiper.scrollbar.onDragEnd, passiveListener);
      } else {
        target.addEventListener(touchEventsTouch.start, swiper.scrollbar.onDragStart, activeListener);
        target.addEventListener(touchEventsTouch.move, swiper.scrollbar.onDragMove, activeListener);
        target.addEventListener(touchEventsTouch.end, swiper.scrollbar.onDragEnd, passiveListener);
      }
    },
    disableDraggable: function disableDraggable() {
      var swiper = this;
      if (!swiper.params.scrollbar.el) return;
      var document = getDocument();
      var scrollbar = swiper.scrollbar,
          touchEventsTouch = swiper.touchEventsTouch,
          touchEventsDesktop = swiper.touchEventsDesktop,
          params = swiper.params,
          support = swiper.support;
      var $el = scrollbar.$el;
      var target = $el[0];
      var activeListener = support.passiveListener && params.passiveListeners ? {
        passive: false,
        capture: false
      } : false;
      var passiveListener = support.passiveListener && params.passiveListeners ? {
        passive: true,
        capture: false
      } : false;
      if (!target) return;

      if (!support.touch) {
        target.removeEventListener(touchEventsDesktop.start, swiper.scrollbar.onDragStart, activeListener);
        document.removeEventListener(touchEventsDesktop.move, swiper.scrollbar.onDragMove, activeListener);
        document.removeEventListener(touchEventsDesktop.end, swiper.scrollbar.onDragEnd, passiveListener);
      } else {
        target.removeEventListener(touchEventsTouch.start, swiper.scrollbar.onDragStart, activeListener);
        target.removeEventListener(touchEventsTouch.move, swiper.scrollbar.onDragMove, activeListener);
        target.removeEventListener(touchEventsTouch.end, swiper.scrollbar.onDragEnd, passiveListener);
      }
    },
    init: function init() {
      var swiper = this;
      var scrollbar = swiper.scrollbar,
          $swiperEl = swiper.$el;
      swiper.params.scrollbar = createElementIfNotDefined($swiperEl, swiper.params.scrollbar, swiper.params.createElements, {
        el: 'swiper-scrollbar'
      });
      var params = swiper.params.scrollbar;
      if (!params.el) return;
      var $el = $(params.el);

      if (swiper.params.uniqueNavElements && typeof params.el === 'string' && $el.length > 1 && $swiperEl.find(params.el).length === 1) {
        $el = $swiperEl.find(params.el);
      }

      var $dragEl = $el.find("." + swiper.params.scrollbar.dragClass);

      if ($dragEl.length === 0) {
        $dragEl = $("<div class=\"" + swiper.params.scrollbar.dragClass + "\"></div>");
        $el.append($dragEl);
      }

      extend(scrollbar, {
        $el: $el,
        el: $el[0],
        $dragEl: $dragEl,
        dragEl: $dragEl[0]
      });

      if (params.draggable) {
        scrollbar.enableDraggable();
      }

      if ($el) {
        $el[swiper.enabled ? 'removeClass' : 'addClass'](swiper.params.scrollbar.lockClass);
      }
    },
    destroy: function destroy() {
      var swiper = this;
      swiper.scrollbar.disableDraggable();
    }
  };
  var Scrollbar$1 = {
    name: 'scrollbar',
    params: {
      scrollbar: {
        el: null,
        dragSize: 'auto',
        hide: false,
        draggable: false,
        snapOnRelease: true,
        lockClass: 'swiper-scrollbar-lock',
        dragClass: 'swiper-scrollbar-drag'
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        scrollbar: _extends({
          isTouched: false,
          timeout: null,
          dragTimeout: null
        }, Scrollbar)
      });
    },
    on: {
      init: function init(swiper) {
        swiper.scrollbar.init();
        swiper.scrollbar.updateSize();
        swiper.scrollbar.setTranslate();
      },
      update: function update(swiper) {
        swiper.scrollbar.updateSize();
      },
      resize: function resize(swiper) {
        swiper.scrollbar.updateSize();
      },
      observerUpdate: function observerUpdate(swiper) {
        swiper.scrollbar.updateSize();
      },
      setTranslate: function setTranslate(swiper) {
        swiper.scrollbar.setTranslate();
      },
      setTransition: function setTransition(swiper, duration) {
        swiper.scrollbar.setTransition(duration);
      },
      'enable disable': function enableDisable(swiper) {
        var $el = swiper.scrollbar.$el;

        if ($el) {
          $el[swiper.enabled ? 'removeClass' : 'addClass'](swiper.params.scrollbar.lockClass);
        }
      },
      destroy: function destroy(swiper) {
        swiper.scrollbar.destroy();
      }
    }
  };
  var Parallax = {
    setTransform: function setTransform(el, progress) {
      var swiper = this;
      var rtl = swiper.rtl;
      var $el = $(el);
      var rtlFactor = rtl ? -1 : 1;
      var p = $el.attr('data-swiper-parallax') || '0';
      var x = $el.attr('data-swiper-parallax-x');
      var y = $el.attr('data-swiper-parallax-y');
      var scale = $el.attr('data-swiper-parallax-scale');
      var opacity = $el.attr('data-swiper-parallax-opacity');

      if (x || y) {
        x = x || '0';
        y = y || '0';
      } else if (swiper.isHorizontal()) {
        x = p;
        y = '0';
      } else {
        y = p;
        x = '0';
      }

      if (x.indexOf('%') >= 0) {
        x = parseInt(x, 10) * progress * rtlFactor + "%";
      } else {
        x = x * progress * rtlFactor + "px";
      }

      if (y.indexOf('%') >= 0) {
        y = parseInt(y, 10) * progress + "%";
      } else {
        y = y * progress + "px";
      }

      if (typeof opacity !== 'undefined' && opacity !== null) {
        var currentOpacity = opacity - (opacity - 1) * (1 - Math.abs(progress));
        $el[0].style.opacity = currentOpacity;
      }

      if (typeof scale === 'undefined' || scale === null) {
        $el.transform("translate3d(" + x + ", " + y + ", 0px)");
      } else {
        var currentScale = scale - (scale - 1) * (1 - Math.abs(progress));
        $el.transform("translate3d(" + x + ", " + y + ", 0px) scale(" + currentScale + ")");
      }
    },
    setTranslate: function setTranslate() {
      var swiper = this;
      var $el = swiper.$el,
          slides = swiper.slides,
          progress = swiper.progress,
          snapGrid = swiper.snapGrid;
      $el.children('[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y], [data-swiper-parallax-opacity], [data-swiper-parallax-scale]').each(function (el) {
        swiper.parallax.setTransform(el, progress);
      });
      slides.each(function (slideEl, slideIndex) {
        var slideProgress = slideEl.progress;

        if (swiper.params.slidesPerGroup > 1 && swiper.params.slidesPerView !== 'auto') {
          slideProgress += Math.ceil(slideIndex / 2) - progress * (snapGrid.length - 1);
        }

        slideProgress = Math.min(Math.max(slideProgress, -1), 1);
        $(slideEl).find('[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y], [data-swiper-parallax-opacity], [data-swiper-parallax-scale]').each(function (el) {
          swiper.parallax.setTransform(el, slideProgress);
        });
      });
    },
    setTransition: function setTransition(duration) {
      if (duration === void 0) {
        duration = this.params.speed;
      }

      var swiper = this;
      var $el = swiper.$el;
      $el.find('[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y], [data-swiper-parallax-opacity], [data-swiper-parallax-scale]').each(function (parallaxEl) {
        var $parallaxEl = $(parallaxEl);
        var parallaxDuration = parseInt($parallaxEl.attr('data-swiper-parallax-duration'), 10) || duration;
        if (duration === 0) parallaxDuration = 0;
        $parallaxEl.transition(parallaxDuration);
      });
    }
  };
  var Parallax$1 = {
    name: 'parallax',
    params: {
      parallax: {
        enabled: false
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        parallax: _extends({}, Parallax)
      });
    },
    on: {
      beforeInit: function beforeInit(swiper) {
        if (!swiper.params.parallax.enabled) return;
        swiper.params.watchSlidesProgress = true;
        swiper.originalParams.watchSlidesProgress = true;
      },
      init: function init(swiper) {
        if (!swiper.params.parallax.enabled) return;
        swiper.parallax.setTranslate();
      },
      setTranslate: function setTranslate(swiper) {
        if (!swiper.params.parallax.enabled) return;
        swiper.parallax.setTranslate();
      },
      setTransition: function setTransition(swiper, duration) {
        if (!swiper.params.parallax.enabled) return;
        swiper.parallax.setTransition(duration);
      }
    }
  };
  var Zoom = {
    // Calc Scale From Multi-touches
    getDistanceBetweenTouches: function getDistanceBetweenTouches(e) {
      if (e.targetTouches.length < 2) return 1;
      var x1 = e.targetTouches[0].pageX;
      var y1 = e.targetTouches[0].pageY;
      var x2 = e.targetTouches[1].pageX;
      var y2 = e.targetTouches[1].pageY;
      var distance = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
      return distance;
    },
    // Events
    onGestureStart: function onGestureStart(e) {
      var swiper = this;
      var support = swiper.support;
      var params = swiper.params.zoom;
      var zoom = swiper.zoom;
      var gesture = zoom.gesture;
      zoom.fakeGestureTouched = false;
      zoom.fakeGestureMoved = false;

      if (!support.gestures) {
        if (e.type !== 'touchstart' || e.type === 'touchstart' && e.targetTouches.length < 2) {
          return;
        }

        zoom.fakeGestureTouched = true;
        gesture.scaleStart = Zoom.getDistanceBetweenTouches(e);
      }

      if (!gesture.$slideEl || !gesture.$slideEl.length) {
        gesture.$slideEl = $(e.target).closest("." + swiper.params.slideClass);
        if (gesture.$slideEl.length === 0) gesture.$slideEl = swiper.slides.eq(swiper.activeIndex);
        gesture.$imageEl = gesture.$slideEl.find('img, svg, canvas, picture, .swiper-zoom-target');
        gesture.$imageWrapEl = gesture.$imageEl.parent("." + params.containerClass);
        gesture.maxRatio = gesture.$imageWrapEl.attr('data-swiper-zoom') || params.maxRatio;

        if (gesture.$imageWrapEl.length === 0) {
          gesture.$imageEl = undefined;
          return;
        }
      }

      if (gesture.$imageEl) {
        gesture.$imageEl.transition(0);
      }

      swiper.zoom.isScaling = true;
    },
    onGestureChange: function onGestureChange(e) {
      var swiper = this;
      var support = swiper.support;
      var params = swiper.params.zoom;
      var zoom = swiper.zoom;
      var gesture = zoom.gesture;

      if (!support.gestures) {
        if (e.type !== 'touchmove' || e.type === 'touchmove' && e.targetTouches.length < 2) {
          return;
        }

        zoom.fakeGestureMoved = true;
        gesture.scaleMove = Zoom.getDistanceBetweenTouches(e);
      }

      if (!gesture.$imageEl || gesture.$imageEl.length === 0) {
        if (e.type === 'gesturechange') zoom.onGestureStart(e);
        return;
      }

      if (support.gestures) {
        zoom.scale = e.scale * zoom.currentScale;
      } else {
        zoom.scale = gesture.scaleMove / gesture.scaleStart * zoom.currentScale;
      }

      if (zoom.scale > gesture.maxRatio) {
        zoom.scale = gesture.maxRatio - 1 + Math.pow(zoom.scale - gesture.maxRatio + 1, 0.5);
      }

      if (zoom.scale < params.minRatio) {
        zoom.scale = params.minRatio + 1 - Math.pow(params.minRatio - zoom.scale + 1, 0.5);
      }

      gesture.$imageEl.transform("translate3d(0,0,0) scale(" + zoom.scale + ")");
    },
    onGestureEnd: function onGestureEnd(e) {
      var swiper = this;
      var device = swiper.device;
      var support = swiper.support;
      var params = swiper.params.zoom;
      var zoom = swiper.zoom;
      var gesture = zoom.gesture;

      if (!support.gestures) {
        if (!zoom.fakeGestureTouched || !zoom.fakeGestureMoved) {
          return;
        }

        if (e.type !== 'touchend' || e.type === 'touchend' && e.changedTouches.length < 2 && !device.android) {
          return;
        }

        zoom.fakeGestureTouched = false;
        zoom.fakeGestureMoved = false;
      }

      if (!gesture.$imageEl || gesture.$imageEl.length === 0) return;
      zoom.scale = Math.max(Math.min(zoom.scale, gesture.maxRatio), params.minRatio);
      gesture.$imageEl.transition(swiper.params.speed).transform("translate3d(0,0,0) scale(" + zoom.scale + ")");
      zoom.currentScale = zoom.scale;
      zoom.isScaling = false;
      if (zoom.scale === 1) gesture.$slideEl = undefined;
    },
    onTouchStart: function onTouchStart(e) {
      var swiper = this;
      var device = swiper.device;
      var zoom = swiper.zoom;
      var gesture = zoom.gesture,
          image = zoom.image;
      if (!gesture.$imageEl || gesture.$imageEl.length === 0) return;
      if (image.isTouched) return;
      if (device.android && e.cancelable) e.preventDefault();
      image.isTouched = true;
      image.touchesStart.x = e.type === 'touchstart' ? e.targetTouches[0].pageX : e.pageX;
      image.touchesStart.y = e.type === 'touchstart' ? e.targetTouches[0].pageY : e.pageY;
    },
    onTouchMove: function onTouchMove(e) {
      var swiper = this;
      var zoom = swiper.zoom;
      var gesture = zoom.gesture,
          image = zoom.image,
          velocity = zoom.velocity;
      if (!gesture.$imageEl || gesture.$imageEl.length === 0) return;
      swiper.allowClick = false;
      if (!image.isTouched || !gesture.$slideEl) return;

      if (!image.isMoved) {
        image.width = gesture.$imageEl[0].offsetWidth;
        image.height = gesture.$imageEl[0].offsetHeight;
        image.startX = getTranslate(gesture.$imageWrapEl[0], 'x') || 0;
        image.startY = getTranslate(gesture.$imageWrapEl[0], 'y') || 0;
        gesture.slideWidth = gesture.$slideEl[0].offsetWidth;
        gesture.slideHeight = gesture.$slideEl[0].offsetHeight;
        gesture.$imageWrapEl.transition(0);
      } // Define if we need image drag


      var scaledWidth = image.width * zoom.scale;
      var scaledHeight = image.height * zoom.scale;
      if (scaledWidth < gesture.slideWidth && scaledHeight < gesture.slideHeight) return;
      image.minX = Math.min(gesture.slideWidth / 2 - scaledWidth / 2, 0);
      image.maxX = -image.minX;
      image.minY = Math.min(gesture.slideHeight / 2 - scaledHeight / 2, 0);
      image.maxY = -image.minY;
      image.touchesCurrent.x = e.type === 'touchmove' ? e.targetTouches[0].pageX : e.pageX;
      image.touchesCurrent.y = e.type === 'touchmove' ? e.targetTouches[0].pageY : e.pageY;

      if (!image.isMoved && !zoom.isScaling) {
        if (swiper.isHorizontal() && (Math.floor(image.minX) === Math.floor(image.startX) && image.touchesCurrent.x < image.touchesStart.x || Math.floor(image.maxX) === Math.floor(image.startX) && image.touchesCurrent.x > image.touchesStart.x)) {
          image.isTouched = false;
          return;
        }

        if (!swiper.isHorizontal() && (Math.floor(image.minY) === Math.floor(image.startY) && image.touchesCurrent.y < image.touchesStart.y || Math.floor(image.maxY) === Math.floor(image.startY) && image.touchesCurrent.y > image.touchesStart.y)) {
          image.isTouched = false;
          return;
        }
      }

      if (e.cancelable) {
        e.preventDefault();
      }

      e.stopPropagation();
      image.isMoved = true;
      image.currentX = image.touchesCurrent.x - image.touchesStart.x + image.startX;
      image.currentY = image.touchesCurrent.y - image.touchesStart.y + image.startY;

      if (image.currentX < image.minX) {
        image.currentX = image.minX + 1 - Math.pow(image.minX - image.currentX + 1, 0.8);
      }

      if (image.currentX > image.maxX) {
        image.currentX = image.maxX - 1 + Math.pow(image.currentX - image.maxX + 1, 0.8);
      }

      if (image.currentY < image.minY) {
        image.currentY = image.minY + 1 - Math.pow(image.minY - image.currentY + 1, 0.8);
      }

      if (image.currentY > image.maxY) {
        image.currentY = image.maxY - 1 + Math.pow(image.currentY - image.maxY + 1, 0.8);
      } // Velocity


      if (!velocity.prevPositionX) velocity.prevPositionX = image.touchesCurrent.x;
      if (!velocity.prevPositionY) velocity.prevPositionY = image.touchesCurrent.y;
      if (!velocity.prevTime) velocity.prevTime = Date.now();
      velocity.x = (image.touchesCurrent.x - velocity.prevPositionX) / (Date.now() - velocity.prevTime) / 2;
      velocity.y = (image.touchesCurrent.y - velocity.prevPositionY) / (Date.now() - velocity.prevTime) / 2;
      if (Math.abs(image.touchesCurrent.x - velocity.prevPositionX) < 2) velocity.x = 0;
      if (Math.abs(image.touchesCurrent.y - velocity.prevPositionY) < 2) velocity.y = 0;
      velocity.prevPositionX = image.touchesCurrent.x;
      velocity.prevPositionY = image.touchesCurrent.y;
      velocity.prevTime = Date.now();
      gesture.$imageWrapEl.transform("translate3d(" + image.currentX + "px, " + image.currentY + "px,0)");
    },
    onTouchEnd: function onTouchEnd() {
      var swiper = this;
      var zoom = swiper.zoom;
      var gesture = zoom.gesture,
          image = zoom.image,
          velocity = zoom.velocity;
      if (!gesture.$imageEl || gesture.$imageEl.length === 0) return;

      if (!image.isTouched || !image.isMoved) {
        image.isTouched = false;
        image.isMoved = false;
        return;
      }

      image.isTouched = false;
      image.isMoved = false;
      var momentumDurationX = 300;
      var momentumDurationY = 300;
      var momentumDistanceX = velocity.x * momentumDurationX;
      var newPositionX = image.currentX + momentumDistanceX;
      var momentumDistanceY = velocity.y * momentumDurationY;
      var newPositionY = image.currentY + momentumDistanceY; // Fix duration

      if (velocity.x !== 0) momentumDurationX = Math.abs((newPositionX - image.currentX) / velocity.x);
      if (velocity.y !== 0) momentumDurationY = Math.abs((newPositionY - image.currentY) / velocity.y);
      var momentumDuration = Math.max(momentumDurationX, momentumDurationY);
      image.currentX = newPositionX;
      image.currentY = newPositionY; // Define if we need image drag

      var scaledWidth = image.width * zoom.scale;
      var scaledHeight = image.height * zoom.scale;
      image.minX = Math.min(gesture.slideWidth / 2 - scaledWidth / 2, 0);
      image.maxX = -image.minX;
      image.minY = Math.min(gesture.slideHeight / 2 - scaledHeight / 2, 0);
      image.maxY = -image.minY;
      image.currentX = Math.max(Math.min(image.currentX, image.maxX), image.minX);
      image.currentY = Math.max(Math.min(image.currentY, image.maxY), image.minY);
      gesture.$imageWrapEl.transition(momentumDuration).transform("translate3d(" + image.currentX + "px, " + image.currentY + "px,0)");
    },
    onTransitionEnd: function onTransitionEnd() {
      var swiper = this;
      var zoom = swiper.zoom;
      var gesture = zoom.gesture;

      if (gesture.$slideEl && swiper.previousIndex !== swiper.activeIndex) {
        if (gesture.$imageEl) {
          gesture.$imageEl.transform('translate3d(0,0,0) scale(1)');
        }

        if (gesture.$imageWrapEl) {
          gesture.$imageWrapEl.transform('translate3d(0,0,0)');
        }

        zoom.scale = 1;
        zoom.currentScale = 1;
        gesture.$slideEl = undefined;
        gesture.$imageEl = undefined;
        gesture.$imageWrapEl = undefined;
      }
    },
    // Toggle Zoom
    toggle: function toggle(e) {
      var swiper = this;
      var zoom = swiper.zoom;

      if (zoom.scale && zoom.scale !== 1) {
        // Zoom Out
        zoom.out();
      } else {
        // Zoom In
        zoom["in"](e);
      }
    },
    "in": function _in(e) {
      var swiper = this;
      var window = getWindow();
      var zoom = swiper.zoom;
      var params = swiper.params.zoom;
      var gesture = zoom.gesture,
          image = zoom.image;

      if (!gesture.$slideEl) {
        if (e && e.target) {
          gesture.$slideEl = $(e.target).closest("." + swiper.params.slideClass);
        }

        if (!gesture.$slideEl) {
          if (swiper.params.virtual && swiper.params.virtual.enabled && swiper.virtual) {
            gesture.$slideEl = swiper.$wrapperEl.children("." + swiper.params.slideActiveClass);
          } else {
            gesture.$slideEl = swiper.slides.eq(swiper.activeIndex);
          }
        }

        gesture.$imageEl = gesture.$slideEl.find('img, svg, canvas, picture, .swiper-zoom-target');
        gesture.$imageWrapEl = gesture.$imageEl.parent("." + params.containerClass);
      }

      if (!gesture.$imageEl || gesture.$imageEl.length === 0 || !gesture.$imageWrapEl || gesture.$imageWrapEl.length === 0) return;
      gesture.$slideEl.addClass("" + params.zoomedSlideClass);
      var touchX;
      var touchY;
      var offsetX;
      var offsetY;
      var diffX;
      var diffY;
      var translateX;
      var translateY;
      var imageWidth;
      var imageHeight;
      var scaledWidth;
      var scaledHeight;
      var translateMinX;
      var translateMinY;
      var translateMaxX;
      var translateMaxY;
      var slideWidth;
      var slideHeight;

      if (typeof image.touchesStart.x === 'undefined' && e) {
        touchX = e.type === 'touchend' ? e.changedTouches[0].pageX : e.pageX;
        touchY = e.type === 'touchend' ? e.changedTouches[0].pageY : e.pageY;
      } else {
        touchX = image.touchesStart.x;
        touchY = image.touchesStart.y;
      }

      zoom.scale = gesture.$imageWrapEl.attr('data-swiper-zoom') || params.maxRatio;
      zoom.currentScale = gesture.$imageWrapEl.attr('data-swiper-zoom') || params.maxRatio;

      if (e) {
        slideWidth = gesture.$slideEl[0].offsetWidth;
        slideHeight = gesture.$slideEl[0].offsetHeight;
        offsetX = gesture.$slideEl.offset().left + window.scrollX;
        offsetY = gesture.$slideEl.offset().top + window.scrollY;
        diffX = offsetX + slideWidth / 2 - touchX;
        diffY = offsetY + slideHeight / 2 - touchY;
        imageWidth = gesture.$imageEl[0].offsetWidth;
        imageHeight = gesture.$imageEl[0].offsetHeight;
        scaledWidth = imageWidth * zoom.scale;
        scaledHeight = imageHeight * zoom.scale;
        translateMinX = Math.min(slideWidth / 2 - scaledWidth / 2, 0);
        translateMinY = Math.min(slideHeight / 2 - scaledHeight / 2, 0);
        translateMaxX = -translateMinX;
        translateMaxY = -translateMinY;
        translateX = diffX * zoom.scale;
        translateY = diffY * zoom.scale;

        if (translateX < translateMinX) {
          translateX = translateMinX;
        }

        if (translateX > translateMaxX) {
          translateX = translateMaxX;
        }

        if (translateY < translateMinY) {
          translateY = translateMinY;
        }

        if (translateY > translateMaxY) {
          translateY = translateMaxY;
        }
      } else {
        translateX = 0;
        translateY = 0;
      }

      gesture.$imageWrapEl.transition(300).transform("translate3d(" + translateX + "px, " + translateY + "px,0)");
      gesture.$imageEl.transition(300).transform("translate3d(0,0,0) scale(" + zoom.scale + ")");
    },
    out: function out() {
      var swiper = this;
      var zoom = swiper.zoom;
      var params = swiper.params.zoom;
      var gesture = zoom.gesture;

      if (!gesture.$slideEl) {
        if (swiper.params.virtual && swiper.params.virtual.enabled && swiper.virtual) {
          gesture.$slideEl = swiper.$wrapperEl.children("." + swiper.params.slideActiveClass);
        } else {
          gesture.$slideEl = swiper.slides.eq(swiper.activeIndex);
        }

        gesture.$imageEl = gesture.$slideEl.find('img, svg, canvas, picture, .swiper-zoom-target');
        gesture.$imageWrapEl = gesture.$imageEl.parent("." + params.containerClass);
      }

      if (!gesture.$imageEl || gesture.$imageEl.length === 0 || !gesture.$imageWrapEl || gesture.$imageWrapEl.length === 0) return;
      zoom.scale = 1;
      zoom.currentScale = 1;
      gesture.$imageWrapEl.transition(300).transform('translate3d(0,0,0)');
      gesture.$imageEl.transition(300).transform('translate3d(0,0,0) scale(1)');
      gesture.$slideEl.removeClass("" + params.zoomedSlideClass);
      gesture.$slideEl = undefined;
    },
    toggleGestures: function toggleGestures(method) {
      var swiper = this;
      var zoom = swiper.zoom;
      var selector = zoom.slideSelector,
          passive = zoom.passiveListener;
      swiper.$wrapperEl[method]('gesturestart', selector, zoom.onGestureStart, passive);
      swiper.$wrapperEl[method]('gesturechange', selector, zoom.onGestureChange, passive);
      swiper.$wrapperEl[method]('gestureend', selector, zoom.onGestureEnd, passive);
    },
    enableGestures: function enableGestures() {
      if (this.zoom.gesturesEnabled) return;
      this.zoom.gesturesEnabled = true;
      this.zoom.toggleGestures('on');
    },
    disableGestures: function disableGestures() {
      if (!this.zoom.gesturesEnabled) return;
      this.zoom.gesturesEnabled = false;
      this.zoom.toggleGestures('off');
    },
    // Attach/Detach Events
    enable: function enable() {
      var swiper = this;
      var support = swiper.support;
      var zoom = swiper.zoom;
      if (zoom.enabled) return;
      zoom.enabled = true;
      var passiveListener = swiper.touchEvents.start === 'touchstart' && support.passiveListener && swiper.params.passiveListeners ? {
        passive: true,
        capture: false
      } : false;
      var activeListenerWithCapture = support.passiveListener ? {
        passive: false,
        capture: true
      } : true;
      var slideSelector = "." + swiper.params.slideClass;
      swiper.zoom.passiveListener = passiveListener;
      swiper.zoom.slideSelector = slideSelector; // Scale image

      if (support.gestures) {
        swiper.$wrapperEl.on(swiper.touchEvents.start, swiper.zoom.enableGestures, passiveListener);
        swiper.$wrapperEl.on(swiper.touchEvents.end, swiper.zoom.disableGestures, passiveListener);
      } else if (swiper.touchEvents.start === 'touchstart') {
        swiper.$wrapperEl.on(swiper.touchEvents.start, slideSelector, zoom.onGestureStart, passiveListener);
        swiper.$wrapperEl.on(swiper.touchEvents.move, slideSelector, zoom.onGestureChange, activeListenerWithCapture);
        swiper.$wrapperEl.on(swiper.touchEvents.end, slideSelector, zoom.onGestureEnd, passiveListener);

        if (swiper.touchEvents.cancel) {
          swiper.$wrapperEl.on(swiper.touchEvents.cancel, slideSelector, zoom.onGestureEnd, passiveListener);
        }
      } // Move image


      swiper.$wrapperEl.on(swiper.touchEvents.move, "." + swiper.params.zoom.containerClass, zoom.onTouchMove, activeListenerWithCapture);
    },
    disable: function disable() {
      var swiper = this;
      var zoom = swiper.zoom;
      if (!zoom.enabled) return;
      var support = swiper.support;
      swiper.zoom.enabled = false;
      var passiveListener = swiper.touchEvents.start === 'touchstart' && support.passiveListener && swiper.params.passiveListeners ? {
        passive: true,
        capture: false
      } : false;
      var activeListenerWithCapture = support.passiveListener ? {
        passive: false,
        capture: true
      } : true;
      var slideSelector = "." + swiper.params.slideClass; // Scale image

      if (support.gestures) {
        swiper.$wrapperEl.off(swiper.touchEvents.start, swiper.zoom.enableGestures, passiveListener);
        swiper.$wrapperEl.off(swiper.touchEvents.end, swiper.zoom.disableGestures, passiveListener);
      } else if (swiper.touchEvents.start === 'touchstart') {
        swiper.$wrapperEl.off(swiper.touchEvents.start, slideSelector, zoom.onGestureStart, passiveListener);
        swiper.$wrapperEl.off(swiper.touchEvents.move, slideSelector, zoom.onGestureChange, activeListenerWithCapture);
        swiper.$wrapperEl.off(swiper.touchEvents.end, slideSelector, zoom.onGestureEnd, passiveListener);

        if (swiper.touchEvents.cancel) {
          swiper.$wrapperEl.off(swiper.touchEvents.cancel, slideSelector, zoom.onGestureEnd, passiveListener);
        }
      } // Move image


      swiper.$wrapperEl.off(swiper.touchEvents.move, "." + swiper.params.zoom.containerClass, zoom.onTouchMove, activeListenerWithCapture);
    }
  };
  var Zoom$1 = {
    name: 'zoom',
    params: {
      zoom: {
        enabled: false,
        maxRatio: 3,
        minRatio: 1,
        toggle: true,
        containerClass: 'swiper-zoom-container',
        zoomedSlideClass: 'swiper-slide-zoomed'
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        zoom: _extends({
          enabled: false,
          scale: 1,
          currentScale: 1,
          isScaling: false,
          gesture: {
            $slideEl: undefined,
            slideWidth: undefined,
            slideHeight: undefined,
            $imageEl: undefined,
            $imageWrapEl: undefined,
            maxRatio: 3
          },
          image: {
            isTouched: undefined,
            isMoved: undefined,
            currentX: undefined,
            currentY: undefined,
            minX: undefined,
            minY: undefined,
            maxX: undefined,
            maxY: undefined,
            width: undefined,
            height: undefined,
            startX: undefined,
            startY: undefined,
            touchesStart: {},
            touchesCurrent: {}
          },
          velocity: {
            x: undefined,
            y: undefined,
            prevPositionX: undefined,
            prevPositionY: undefined,
            prevTime: undefined
          }
        }, Zoom)
      });
      var scale = 1;
      Object.defineProperty(swiper.zoom, 'scale', {
        get: function get() {
          return scale;
        },
        set: function set(value) {
          if (scale !== value) {
            var imageEl = swiper.zoom.gesture.$imageEl ? swiper.zoom.gesture.$imageEl[0] : undefined;
            var slideEl = swiper.zoom.gesture.$slideEl ? swiper.zoom.gesture.$slideEl[0] : undefined;
            swiper.emit('zoomChange', value, imageEl, slideEl);
          }

          scale = value;
        }
      });
    },
    on: {
      init: function init(swiper) {
        if (swiper.params.zoom.enabled) {
          swiper.zoom.enable();
        }
      },
      destroy: function destroy(swiper) {
        swiper.zoom.disable();
      },
      touchStart: function touchStart(swiper, e) {
        if (!swiper.zoom.enabled) return;
        swiper.zoom.onTouchStart(e);
      },
      touchEnd: function touchEnd(swiper, e) {
        if (!swiper.zoom.enabled) return;
        swiper.zoom.onTouchEnd(e);
      },
      doubleTap: function doubleTap(swiper, e) {
        if (!swiper.animating && swiper.params.zoom.enabled && swiper.zoom.enabled && swiper.params.zoom.toggle) {
          swiper.zoom.toggle(e);
        }
      },
      transitionEnd: function transitionEnd(swiper) {
        if (swiper.zoom.enabled && swiper.params.zoom.enabled) {
          swiper.zoom.onTransitionEnd();
        }
      },
      slideChange: function slideChange(swiper) {
        if (swiper.zoom.enabled && swiper.params.zoom.enabled && swiper.params.cssMode) {
          swiper.zoom.onTransitionEnd();
        }
      }
    }
  };
  var Lazy = {
    loadInSlide: function loadInSlide(index, loadInDuplicate) {
      if (loadInDuplicate === void 0) {
        loadInDuplicate = true;
      }

      var swiper = this;
      var params = swiper.params.lazy;
      if (typeof index === 'undefined') return;
      if (swiper.slides.length === 0) return;
      var isVirtual = swiper.virtual && swiper.params.virtual.enabled;
      var $slideEl = isVirtual ? swiper.$wrapperEl.children("." + swiper.params.slideClass + "[data-swiper-slide-index=\"" + index + "\"]") : swiper.slides.eq(index);
      var $images = $slideEl.find("." + params.elementClass + ":not(." + params.loadedClass + "):not(." + params.loadingClass + ")");

      if ($slideEl.hasClass(params.elementClass) && !$slideEl.hasClass(params.loadedClass) && !$slideEl.hasClass(params.loadingClass)) {
        $images.push($slideEl[0]);
      }

      if ($images.length === 0) return;
      $images.each(function (imageEl) {
        var $imageEl = $(imageEl);
        $imageEl.addClass(params.loadingClass);
        var background = $imageEl.attr('data-background');
        var src = $imageEl.attr('data-src');
        var srcset = $imageEl.attr('data-srcset');
        var sizes = $imageEl.attr('data-sizes');
        var $pictureEl = $imageEl.parent('picture');
        swiper.loadImage($imageEl[0], src || background, srcset, sizes, false, function () {
          if (typeof swiper === 'undefined' || swiper === null || !swiper || swiper && !swiper.params || swiper.destroyed) return;

          if (background) {
            $imageEl.css('background-image', "url(\"" + background + "\")");
            $imageEl.removeAttr('data-background');
          } else {
            if (srcset) {
              $imageEl.attr('srcset', srcset);
              $imageEl.removeAttr('data-srcset');
            }

            if (sizes) {
              $imageEl.attr('sizes', sizes);
              $imageEl.removeAttr('data-sizes');
            }

            if ($pictureEl.length) {
              $pictureEl.children('source').each(function (sourceEl) {
                var $source = $(sourceEl);

                if ($source.attr('data-srcset')) {
                  $source.attr('srcset', $source.attr('data-srcset'));
                  $source.removeAttr('data-srcset');
                }
              });
            }

            if (src) {
              $imageEl.attr('src', src);
              $imageEl.removeAttr('data-src');
            }
          }

          $imageEl.addClass(params.loadedClass).removeClass(params.loadingClass);
          $slideEl.find("." + params.preloaderClass).remove();

          if (swiper.params.loop && loadInDuplicate) {
            var slideOriginalIndex = $slideEl.attr('data-swiper-slide-index');

            if ($slideEl.hasClass(swiper.params.slideDuplicateClass)) {
              var originalSlide = swiper.$wrapperEl.children("[data-swiper-slide-index=\"" + slideOriginalIndex + "\"]:not(." + swiper.params.slideDuplicateClass + ")");
              swiper.lazy.loadInSlide(originalSlide.index(), false);
            } else {
              var duplicatedSlide = swiper.$wrapperEl.children("." + swiper.params.slideDuplicateClass + "[data-swiper-slide-index=\"" + slideOriginalIndex + "\"]");
              swiper.lazy.loadInSlide(duplicatedSlide.index(), false);
            }
          }

          swiper.emit('lazyImageReady', $slideEl[0], $imageEl[0]);

          if (swiper.params.autoHeight) {
            swiper.updateAutoHeight();
          }
        });
        swiper.emit('lazyImageLoad', $slideEl[0], $imageEl[0]);
      });
    },
    load: function load() {
      var swiper = this;
      var $wrapperEl = swiper.$wrapperEl,
          swiperParams = swiper.params,
          slides = swiper.slides,
          activeIndex = swiper.activeIndex;
      var isVirtual = swiper.virtual && swiperParams.virtual.enabled;
      var params = swiperParams.lazy;
      var slidesPerView = swiperParams.slidesPerView;

      if (slidesPerView === 'auto') {
        slidesPerView = 0;
      }

      function slideExist(index) {
        if (isVirtual) {
          if ($wrapperEl.children("." + swiperParams.slideClass + "[data-swiper-slide-index=\"" + index + "\"]").length) {
            return true;
          }
        } else if (slides[index]) return true;

        return false;
      }

      function slideIndex(slideEl) {
        if (isVirtual) {
          return $(slideEl).attr('data-swiper-slide-index');
        }

        return $(slideEl).index();
      }

      if (!swiper.lazy.initialImageLoaded) swiper.lazy.initialImageLoaded = true;

      if (swiper.params.watchSlidesVisibility) {
        $wrapperEl.children("." + swiperParams.slideVisibleClass).each(function (slideEl) {
          var index = isVirtual ? $(slideEl).attr('data-swiper-slide-index') : $(slideEl).index();
          swiper.lazy.loadInSlide(index);
        });
      } else if (slidesPerView > 1) {
        for (var i = activeIndex; i < activeIndex + slidesPerView; i += 1) {
          if (slideExist(i)) swiper.lazy.loadInSlide(i);
        }
      } else {
        swiper.lazy.loadInSlide(activeIndex);
      }

      if (params.loadPrevNext) {
        if (slidesPerView > 1 || params.loadPrevNextAmount && params.loadPrevNextAmount > 1) {
          var amount = params.loadPrevNextAmount;
          var spv = slidesPerView;
          var maxIndex = Math.min(activeIndex + spv + Math.max(amount, spv), slides.length);
          var minIndex = Math.max(activeIndex - Math.max(spv, amount), 0); // Next Slides

          for (var _i = activeIndex + slidesPerView; _i < maxIndex; _i += 1) {
            if (slideExist(_i)) swiper.lazy.loadInSlide(_i);
          } // Prev Slides


          for (var _i2 = minIndex; _i2 < activeIndex; _i2 += 1) {
            if (slideExist(_i2)) swiper.lazy.loadInSlide(_i2);
          }
        } else {
          var nextSlide = $wrapperEl.children("." + swiperParams.slideNextClass);
          if (nextSlide.length > 0) swiper.lazy.loadInSlide(slideIndex(nextSlide));
          var prevSlide = $wrapperEl.children("." + swiperParams.slidePrevClass);
          if (prevSlide.length > 0) swiper.lazy.loadInSlide(slideIndex(prevSlide));
        }
      }
    },
    checkInViewOnLoad: function checkInViewOnLoad() {
      var window = getWindow();
      var swiper = this;
      if (!swiper || swiper.destroyed) return;
      var $scrollElement = swiper.params.lazy.scrollingElement ? $(swiper.params.lazy.scrollingElement) : $(window);
      var isWindow = $scrollElement[0] === window;
      var scrollElementWidth = isWindow ? window.innerWidth : $scrollElement[0].offsetWidth;
      var scrollElementHeight = isWindow ? window.innerHeight : $scrollElement[0].offsetHeight;
      var swiperOffset = swiper.$el.offset();
      var rtl = swiper.rtlTranslate;
      var inView = false;
      if (rtl) swiperOffset.left -= swiper.$el[0].scrollLeft;
      var swiperCoord = [[swiperOffset.left, swiperOffset.top], [swiperOffset.left + swiper.width, swiperOffset.top], [swiperOffset.left, swiperOffset.top + swiper.height], [swiperOffset.left + swiper.width, swiperOffset.top + swiper.height]];

      for (var i = 0; i < swiperCoord.length; i += 1) {
        var point = swiperCoord[i];

        if (point[0] >= 0 && point[0] <= scrollElementWidth && point[1] >= 0 && point[1] <= scrollElementHeight) {
          if (point[0] === 0 && point[1] === 0) continue; // eslint-disable-line

          inView = true;
        }
      }

      var passiveListener = swiper.touchEvents.start === 'touchstart' && swiper.support.passiveListener && swiper.params.passiveListeners ? {
        passive: true,
        capture: false
      } : false;

      if (inView) {
        swiper.lazy.load();
        $scrollElement.off('scroll', swiper.lazy.checkInViewOnLoad, passiveListener);
      } else if (!swiper.lazy.scrollHandlerAttached) {
        swiper.lazy.scrollHandlerAttached = true;
        $scrollElement.on('scroll', swiper.lazy.checkInViewOnLoad, passiveListener);
      }
    }
  };
  var Lazy$1 = {
    name: 'lazy',
    params: {
      lazy: {
        checkInView: false,
        enabled: false,
        loadPrevNext: false,
        loadPrevNextAmount: 1,
        loadOnTransitionStart: false,
        scrollingElement: '',
        elementClass: 'swiper-lazy',
        loadingClass: 'swiper-lazy-loading',
        loadedClass: 'swiper-lazy-loaded',
        preloaderClass: 'swiper-lazy-preloader'
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        lazy: _extends({
          initialImageLoaded: false
        }, Lazy)
      });
    },
    on: {
      beforeInit: function beforeInit(swiper) {
        if (swiper.params.lazy.enabled && swiper.params.preloadImages) {
          swiper.params.preloadImages = false;
        }
      },
      init: function init(swiper) {
        if (swiper.params.lazy.enabled && !swiper.params.loop && swiper.params.initialSlide === 0) {
          if (swiper.params.lazy.checkInView) {
            swiper.lazy.checkInViewOnLoad();
          } else {
            swiper.lazy.load();
          }
        }
      },
      scroll: function scroll(swiper) {
        if (swiper.params.freeMode && !swiper.params.freeModeSticky) {
          swiper.lazy.load();
        }
      },
      'scrollbarDragMove resize _freeModeNoMomentumRelease': function lazyLoad(swiper) {
        if (swiper.params.lazy.enabled) {
          swiper.lazy.load();
        }
      },
      transitionStart: function transitionStart(swiper) {
        if (swiper.params.lazy.enabled) {
          if (swiper.params.lazy.loadOnTransitionStart || !swiper.params.lazy.loadOnTransitionStart && !swiper.lazy.initialImageLoaded) {
            swiper.lazy.load();
          }
        }
      },
      transitionEnd: function transitionEnd(swiper) {
        if (swiper.params.lazy.enabled && !swiper.params.lazy.loadOnTransitionStart) {
          swiper.lazy.load();
        }
      },
      slideChange: function slideChange(swiper) {
        var _swiper$params = swiper.params,
            lazy = _swiper$params.lazy,
            cssMode = _swiper$params.cssMode,
            watchSlidesVisibility = _swiper$params.watchSlidesVisibility,
            watchSlidesProgress = _swiper$params.watchSlidesProgress,
            touchReleaseOnEdges = _swiper$params.touchReleaseOnEdges,
            resistanceRatio = _swiper$params.resistanceRatio;

        if (lazy.enabled && (cssMode || (watchSlidesVisibility || watchSlidesProgress) && (touchReleaseOnEdges || resistanceRatio === 0))) {
          swiper.lazy.load();
        }
      }
    }
  };
  var Controller = {
    LinearSpline: function LinearSpline(x, y) {
      var binarySearch = function search() {
        var maxIndex;
        var minIndex;
        var guess;
        return function (array, val) {
          minIndex = -1;
          maxIndex = array.length;

          while (maxIndex - minIndex > 1) {
            guess = maxIndex + minIndex >> 1;

            if (array[guess] <= val) {
              minIndex = guess;
            } else {
              maxIndex = guess;
            }
          }

          return maxIndex;
        };
      }();

      this.x = x;
      this.y = y;
      this.lastIndex = x.length - 1; // Given an x value (x2), return the expected y2 value:
      // (x1,y1) is the known point before given value,
      // (x3,y3) is the known point after given value.

      var i1;
      var i3;

      this.interpolate = function interpolate(x2) {
        if (!x2) return 0; // Get the indexes of x1 and x3 (the array indexes before and after given x2):

        i3 = binarySearch(this.x, x2);
        i1 = i3 - 1; // We have our indexes i1 & i3, so we can calculate already:
        // y2 := ((x2−x1) × (y3−y1)) ÷ (x3−x1) + y1

        return (x2 - this.x[i1]) * (this.y[i3] - this.y[i1]) / (this.x[i3] - this.x[i1]) + this.y[i1];
      };

      return this;
    },
    // xxx: for now i will just save one spline function to to
    getInterpolateFunction: function getInterpolateFunction(c) {
      var swiper = this;

      if (!swiper.controller.spline) {
        swiper.controller.spline = swiper.params.loop ? new Controller.LinearSpline(swiper.slidesGrid, c.slidesGrid) : new Controller.LinearSpline(swiper.snapGrid, c.snapGrid);
      }
    },
    setTranslate: function setTranslate(_setTranslate, byController) {
      var swiper = this;
      var controlled = swiper.controller.control;
      var multiplier;
      var controlledTranslate;
      var Swiper = swiper.constructor;

      function setControlledTranslate(c) {
        // this will create an Interpolate function based on the snapGrids
        // x is the Grid of the scrolled scroller and y will be the controlled scroller
        // it makes sense to create this only once and recall it for the interpolation
        // the function does a lot of value caching for performance
        var translate = swiper.rtlTranslate ? -swiper.translate : swiper.translate;

        if (swiper.params.controller.by === 'slide') {
          swiper.controller.getInterpolateFunction(c); // i am not sure why the values have to be multiplicated this way, tried to invert the snapGrid
          // but it did not work out

          controlledTranslate = -swiper.controller.spline.interpolate(-translate);
        }

        if (!controlledTranslate || swiper.params.controller.by === 'container') {
          multiplier = (c.maxTranslate() - c.minTranslate()) / (swiper.maxTranslate() - swiper.minTranslate());
          controlledTranslate = (translate - swiper.minTranslate()) * multiplier + c.minTranslate();
        }

        if (swiper.params.controller.inverse) {
          controlledTranslate = c.maxTranslate() - controlledTranslate;
        }

        c.updateProgress(controlledTranslate);
        c.setTranslate(controlledTranslate, swiper);
        c.updateActiveIndex();
        c.updateSlidesClasses();
      }

      if (Array.isArray(controlled)) {
        for (var i = 0; i < controlled.length; i += 1) {
          if (controlled[i] !== byController && controlled[i] instanceof Swiper) {
            setControlledTranslate(controlled[i]);
          }
        }
      } else if (controlled instanceof Swiper && byController !== controlled) {
        setControlledTranslate(controlled);
      }
    },
    setTransition: function setTransition(duration, byController) {
      var swiper = this;
      var Swiper = swiper.constructor;
      var controlled = swiper.controller.control;
      var i;

      function setControlledTransition(c) {
        c.setTransition(duration, swiper);

        if (duration !== 0) {
          c.transitionStart();

          if (c.params.autoHeight) {
            nextTick(function () {
              c.updateAutoHeight();
            });
          }

          c.$wrapperEl.transitionEnd(function () {
            if (!controlled) return;

            if (c.params.loop && swiper.params.controller.by === 'slide') {
              c.loopFix();
            }

            c.transitionEnd();
          });
        }
      }

      if (Array.isArray(controlled)) {
        for (i = 0; i < controlled.length; i += 1) {
          if (controlled[i] !== byController && controlled[i] instanceof Swiper) {
            setControlledTransition(controlled[i]);
          }
        }
      } else if (controlled instanceof Swiper && byController !== controlled) {
        setControlledTransition(controlled);
      }
    }
  };
  var Controller$1 = {
    name: 'controller',
    params: {
      controller: {
        control: undefined,
        inverse: false,
        by: 'slide' // or 'container'

      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        controller: _extends({
          control: swiper.params.controller.control
        }, Controller)
      });
    },
    on: {
      update: function update(swiper) {
        if (!swiper.controller.control) return;

        if (swiper.controller.spline) {
          swiper.controller.spline = undefined;
          delete swiper.controller.spline;
        }
      },
      resize: function resize(swiper) {
        if (!swiper.controller.control) return;

        if (swiper.controller.spline) {
          swiper.controller.spline = undefined;
          delete swiper.controller.spline;
        }
      },
      observerUpdate: function observerUpdate(swiper) {
        if (!swiper.controller.control) return;

        if (swiper.controller.spline) {
          swiper.controller.spline = undefined;
          delete swiper.controller.spline;
        }
      },
      setTranslate: function setTranslate(swiper, translate, byController) {
        if (!swiper.controller.control) return;
        swiper.controller.setTranslate(translate, byController);
      },
      setTransition: function setTransition(swiper, duration, byController) {
        if (!swiper.controller.control) return;
        swiper.controller.setTransition(duration, byController);
      }
    }
  };
  var A11y = {
    getRandomNumber: function getRandomNumber(size) {
      if (size === void 0) {
        size = 16;
      }

      var randomChar = function randomChar() {
        return Math.round(16 * Math.random()).toString(16);
      };

      return 'x'.repeat(size).replace(/x/g, randomChar);
    },
    makeElFocusable: function makeElFocusable($el) {
      $el.attr('tabIndex', '0');
      return $el;
    },
    makeElNotFocusable: function makeElNotFocusable($el) {
      $el.attr('tabIndex', '-1');
      return $el;
    },
    addElRole: function addElRole($el, role) {
      $el.attr('role', role);
      return $el;
    },
    addElRoleDescription: function addElRoleDescription($el, description) {
      $el.attr('aria-roledescription', description);
      return $el;
    },
    addElControls: function addElControls($el, controls) {
      $el.attr('aria-controls', controls);
      return $el;
    },
    addElLabel: function addElLabel($el, label) {
      $el.attr('aria-label', label);
      return $el;
    },
    addElId: function addElId($el, id) {
      $el.attr('id', id);
      return $el;
    },
    addElLive: function addElLive($el, live) {
      $el.attr('aria-live', live);
      return $el;
    },
    disableEl: function disableEl($el) {
      $el.attr('aria-disabled', true);
      return $el;
    },
    enableEl: function enableEl($el) {
      $el.attr('aria-disabled', false);
      return $el;
    },
    onEnterOrSpaceKey: function onEnterOrSpaceKey(e) {
      if (e.keyCode !== 13 && e.keyCode !== 32) return;
      var swiper = this;
      var params = swiper.params.a11y;
      var $targetEl = $(e.target);

      if (swiper.navigation && swiper.navigation.$nextEl && $targetEl.is(swiper.navigation.$nextEl)) {
        if (!(swiper.isEnd && !swiper.params.loop)) {
          swiper.slideNext();
        }

        if (swiper.isEnd) {
          swiper.a11y.notify(params.lastSlideMessage);
        } else {
          swiper.a11y.notify(params.nextSlideMessage);
        }
      }

      if (swiper.navigation && swiper.navigation.$prevEl && $targetEl.is(swiper.navigation.$prevEl)) {
        if (!(swiper.isBeginning && !swiper.params.loop)) {
          swiper.slidePrev();
        }

        if (swiper.isBeginning) {
          swiper.a11y.notify(params.firstSlideMessage);
        } else {
          swiper.a11y.notify(params.prevSlideMessage);
        }
      }

      if (swiper.pagination && $targetEl.is(classesToSelector(swiper.params.pagination.bulletClass))) {
        $targetEl[0].click();
      }
    },
    notify: function notify(message) {
      var swiper = this;
      var notification = swiper.a11y.liveRegion;
      if (notification.length === 0) return;
      notification.html('');
      notification.html(message);
    },
    updateNavigation: function updateNavigation() {
      var swiper = this;
      if (swiper.params.loop || !swiper.navigation) return;
      var _swiper$navigation = swiper.navigation,
          $nextEl = _swiper$navigation.$nextEl,
          $prevEl = _swiper$navigation.$prevEl;

      if ($prevEl && $prevEl.length > 0) {
        if (swiper.isBeginning) {
          swiper.a11y.disableEl($prevEl);
          swiper.a11y.makeElNotFocusable($prevEl);
        } else {
          swiper.a11y.enableEl($prevEl);
          swiper.a11y.makeElFocusable($prevEl);
        }
      }

      if ($nextEl && $nextEl.length > 0) {
        if (swiper.isEnd) {
          swiper.a11y.disableEl($nextEl);
          swiper.a11y.makeElNotFocusable($nextEl);
        } else {
          swiper.a11y.enableEl($nextEl);
          swiper.a11y.makeElFocusable($nextEl);
        }
      }
    },
    updatePagination: function updatePagination() {
      var swiper = this;
      var params = swiper.params.a11y;

      if (swiper.pagination && swiper.params.pagination.clickable && swiper.pagination.bullets && swiper.pagination.bullets.length) {
        swiper.pagination.bullets.each(function (bulletEl) {
          var $bulletEl = $(bulletEl);
          swiper.a11y.makeElFocusable($bulletEl);

          if (!swiper.params.pagination.renderBullet) {
            swiper.a11y.addElRole($bulletEl, 'button');
            swiper.a11y.addElLabel($bulletEl, params.paginationBulletMessage.replace(/\{\{index\}\}/, $bulletEl.index() + 1));
          }
        });
      }
    },
    init: function init() {
      var swiper = this;
      var params = swiper.params.a11y;
      swiper.$el.append(swiper.a11y.liveRegion); // Container

      var $containerEl = swiper.$el;

      if (params.containerRoleDescriptionMessage) {
        swiper.a11y.addElRoleDescription($containerEl, params.containerRoleDescriptionMessage);
      }

      if (params.containerMessage) {
        swiper.a11y.addElLabel($containerEl, params.containerMessage);
      } // Wrapper


      var $wrapperEl = swiper.$wrapperEl;
      var wrapperId = $wrapperEl.attr('id') || "swiper-wrapper-" + swiper.a11y.getRandomNumber(16);
      var live = swiper.params.autoplay && swiper.params.autoplay.enabled ? 'off' : 'polite';
      swiper.a11y.addElId($wrapperEl, wrapperId);
      swiper.a11y.addElLive($wrapperEl, live); // Slide

      if (params.itemRoleDescriptionMessage) {
        swiper.a11y.addElRoleDescription($(swiper.slides), params.itemRoleDescriptionMessage);
      }

      swiper.a11y.addElRole($(swiper.slides), params.slideRole);
      var slidesLength = swiper.params.loop ? swiper.slides.filter(function (el) {
        return !el.classList.contains(swiper.params.slideDuplicateClass);
      }).length : swiper.slides.length;
      swiper.slides.each(function (slideEl, index) {
        var $slideEl = $(slideEl);
        var slideIndex = swiper.params.loop ? parseInt($slideEl.attr('data-swiper-slide-index'), 10) : index;
        var ariaLabelMessage = params.slideLabelMessage.replace(/\{\{index\}\}/, slideIndex + 1).replace(/\{\{slidesLength\}\}/, slidesLength);
        swiper.a11y.addElLabel($slideEl, ariaLabelMessage);
      }); // Navigation

      var $nextEl;
      var $prevEl;

      if (swiper.navigation && swiper.navigation.$nextEl) {
        $nextEl = swiper.navigation.$nextEl;
      }

      if (swiper.navigation && swiper.navigation.$prevEl) {
        $prevEl = swiper.navigation.$prevEl;
      }

      if ($nextEl && $nextEl.length) {
        swiper.a11y.makeElFocusable($nextEl);

        if ($nextEl[0].tagName !== 'BUTTON') {
          swiper.a11y.addElRole($nextEl, 'button');
          $nextEl.on('keydown', swiper.a11y.onEnterOrSpaceKey);
        }

        swiper.a11y.addElLabel($nextEl, params.nextSlideMessage);
        swiper.a11y.addElControls($nextEl, wrapperId);
      }

      if ($prevEl && $prevEl.length) {
        swiper.a11y.makeElFocusable($prevEl);

        if ($prevEl[0].tagName !== 'BUTTON') {
          swiper.a11y.addElRole($prevEl, 'button');
          $prevEl.on('keydown', swiper.a11y.onEnterOrSpaceKey);
        }

        swiper.a11y.addElLabel($prevEl, params.prevSlideMessage);
        swiper.a11y.addElControls($prevEl, wrapperId);
      } // Pagination


      if (swiper.pagination && swiper.params.pagination.clickable && swiper.pagination.bullets && swiper.pagination.bullets.length) {
        swiper.pagination.$el.on('keydown', classesToSelector(swiper.params.pagination.bulletClass), swiper.a11y.onEnterOrSpaceKey);
      }
    },
    destroy: function destroy() {
      var swiper = this;
      if (swiper.a11y.liveRegion && swiper.a11y.liveRegion.length > 0) swiper.a11y.liveRegion.remove();
      var $nextEl;
      var $prevEl;

      if (swiper.navigation && swiper.navigation.$nextEl) {
        $nextEl = swiper.navigation.$nextEl;
      }

      if (swiper.navigation && swiper.navigation.$prevEl) {
        $prevEl = swiper.navigation.$prevEl;
      }

      if ($nextEl) {
        $nextEl.off('keydown', swiper.a11y.onEnterOrSpaceKey);
      }

      if ($prevEl) {
        $prevEl.off('keydown', swiper.a11y.onEnterOrSpaceKey);
      } // Pagination


      if (swiper.pagination && swiper.params.pagination.clickable && swiper.pagination.bullets && swiper.pagination.bullets.length) {
        swiper.pagination.$el.off('keydown', classesToSelector(swiper.params.pagination.bulletClass), swiper.a11y.onEnterOrSpaceKey);
      }
    }
  };
  var A11y$1 = {
    name: 'a11y',
    params: {
      a11y: {
        enabled: true,
        notificationClass: 'swiper-notification',
        prevSlideMessage: 'Previous slide',
        nextSlideMessage: 'Next slide',
        firstSlideMessage: 'This is the first slide',
        lastSlideMessage: 'This is the last slide',
        paginationBulletMessage: 'Go to slide {{index}}',
        slideLabelMessage: '{{index}} / {{slidesLength}}',
        containerMessage: null,
        containerRoleDescriptionMessage: null,
        itemRoleDescriptionMessage: null,
        slideRole: 'group'
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        a11y: _extends({}, A11y, {
          liveRegion: $("<span class=\"" + swiper.params.a11y.notificationClass + "\" aria-live=\"assertive\" aria-atomic=\"true\"></span>")
        })
      });
    },
    on: {
      afterInit: function afterInit(swiper) {
        if (!swiper.params.a11y.enabled) return;
        swiper.a11y.init();
        swiper.a11y.updateNavigation();
      },
      toEdge: function toEdge(swiper) {
        if (!swiper.params.a11y.enabled) return;
        swiper.a11y.updateNavigation();
      },
      fromEdge: function fromEdge(swiper) {
        if (!swiper.params.a11y.enabled) return;
        swiper.a11y.updateNavigation();
      },
      paginationUpdate: function paginationUpdate(swiper) {
        if (!swiper.params.a11y.enabled) return;
        swiper.a11y.updatePagination();
      },
      destroy: function destroy(swiper) {
        if (!swiper.params.a11y.enabled) return;
        swiper.a11y.destroy();
      }
    }
  };
  var History = {
    init: function init() {
      var swiper = this;
      var window = getWindow();
      if (!swiper.params.history) return;

      if (!window.history || !window.history.pushState) {
        swiper.params.history.enabled = false;
        swiper.params.hashNavigation.enabled = true;
        return;
      }

      var history = swiper.history;
      history.initialized = true;
      history.paths = History.getPathValues(swiper.params.url);
      if (!history.paths.key && !history.paths.value) return;
      history.scrollToSlide(0, history.paths.value, swiper.params.runCallbacksOnInit);

      if (!swiper.params.history.replaceState) {
        window.addEventListener('popstate', swiper.history.setHistoryPopState);
      }
    },
    destroy: function destroy() {
      var swiper = this;
      var window = getWindow();

      if (!swiper.params.history.replaceState) {
        window.removeEventListener('popstate', swiper.history.setHistoryPopState);
      }
    },
    setHistoryPopState: function setHistoryPopState() {
      var swiper = this;
      swiper.history.paths = History.getPathValues(swiper.params.url);
      swiper.history.scrollToSlide(swiper.params.speed, swiper.history.paths.value, false);
    },
    getPathValues: function getPathValues(urlOverride) {
      var window = getWindow();
      var location;

      if (urlOverride) {
        location = new URL(urlOverride);
      } else {
        location = window.location;
      }

      var pathArray = location.pathname.slice(1).split('/').filter(function (part) {
        return part !== '';
      });
      var total = pathArray.length;
      var key = pathArray[total - 2];
      var value = pathArray[total - 1];
      return {
        key: key,
        value: value
      };
    },
    setHistory: function setHistory(key, index) {
      var swiper = this;
      var window = getWindow();
      if (!swiper.history.initialized || !swiper.params.history.enabled) return;
      var location;

      if (swiper.params.url) {
        location = new URL(swiper.params.url);
      } else {
        location = window.location;
      }

      var slide = swiper.slides.eq(index);
      var value = History.slugify(slide.attr('data-history'));

      if (swiper.params.history.root.length > 0) {
        var root = swiper.params.history.root;
        if (root[root.length - 1] === '/') root = root.slice(0, root.length - 1);
        value = root + "/" + key + "/" + value;
      } else if (!location.pathname.includes(key)) {
        value = key + "/" + value;
      }

      var currentState = window.history.state;

      if (currentState && currentState.value === value) {
        return;
      }

      if (swiper.params.history.replaceState) {
        window.history.replaceState({
          value: value
        }, null, value);
      } else {
        window.history.pushState({
          value: value
        }, null, value);
      }
    },
    slugify: function slugify(text) {
      return text.toString().replace(/\s+/g, '-').replace(/[^\w-]+/g, '').replace(/--+/g, '-').replace(/^-+/, '').replace(/-+$/, '');
    },
    scrollToSlide: function scrollToSlide(speed, value, runCallbacks) {
      var swiper = this;

      if (value) {
        for (var i = 0, length = swiper.slides.length; i < length; i += 1) {
          var slide = swiper.slides.eq(i);
          var slideHistory = History.slugify(slide.attr('data-history'));

          if (slideHistory === value && !slide.hasClass(swiper.params.slideDuplicateClass)) {
            var index = slide.index();
            swiper.slideTo(index, speed, runCallbacks);
          }
        }
      } else {
        swiper.slideTo(0, speed, runCallbacks);
      }
    }
  };
  var History$1 = {
    name: 'history',
    params: {
      history: {
        enabled: false,
        root: '',
        replaceState: false,
        key: 'slides'
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        history: _extends({}, History)
      });
    },
    on: {
      init: function init(swiper) {
        if (swiper.params.history.enabled) {
          swiper.history.init();
        }
      },
      destroy: function destroy(swiper) {
        if (swiper.params.history.enabled) {
          swiper.history.destroy();
        }
      },
      'transitionEnd _freeModeNoMomentumRelease': function transitionEnd_freeModeNoMomentumRelease(swiper) {
        if (swiper.history.initialized) {
          swiper.history.setHistory(swiper.params.history.key, swiper.activeIndex);
        }
      },
      slideChange: function slideChange(swiper) {
        if (swiper.history.initialized && swiper.params.cssMode) {
          swiper.history.setHistory(swiper.params.history.key, swiper.activeIndex);
        }
      }
    }
  };
  var HashNavigation = {
    onHashChange: function onHashChange() {
      var swiper = this;
      var document = getDocument();
      swiper.emit('hashChange');
      var newHash = document.location.hash.replace('#', '');
      var activeSlideHash = swiper.slides.eq(swiper.activeIndex).attr('data-hash');

      if (newHash !== activeSlideHash) {
        var newIndex = swiper.$wrapperEl.children("." + swiper.params.slideClass + "[data-hash=\"" + newHash + "\"]").index();
        if (typeof newIndex === 'undefined') return;
        swiper.slideTo(newIndex);
      }
    },
    setHash: function setHash() {
      var swiper = this;
      var window = getWindow();
      var document = getDocument();
      if (!swiper.hashNavigation.initialized || !swiper.params.hashNavigation.enabled) return;

      if (swiper.params.hashNavigation.replaceState && window.history && window.history.replaceState) {
        window.history.replaceState(null, null, "#" + swiper.slides.eq(swiper.activeIndex).attr('data-hash') || '');
        swiper.emit('hashSet');
      } else {
        var slide = swiper.slides.eq(swiper.activeIndex);
        var hash = slide.attr('data-hash') || slide.attr('data-history');
        document.location.hash = hash || '';
        swiper.emit('hashSet');
      }
    },
    init: function init() {
      var swiper = this;
      var document = getDocument();
      var window = getWindow();
      if (!swiper.params.hashNavigation.enabled || swiper.params.history && swiper.params.history.enabled) return;
      swiper.hashNavigation.initialized = true;
      var hash = document.location.hash.replace('#', '');

      if (hash) {
        var speed = 0;

        for (var i = 0, length = swiper.slides.length; i < length; i += 1) {
          var slide = swiper.slides.eq(i);
          var slideHash = slide.attr('data-hash') || slide.attr('data-history');

          if (slideHash === hash && !slide.hasClass(swiper.params.slideDuplicateClass)) {
            var index = slide.index();
            swiper.slideTo(index, speed, swiper.params.runCallbacksOnInit, true);
          }
        }
      }

      if (swiper.params.hashNavigation.watchState) {
        $(window).on('hashchange', swiper.hashNavigation.onHashChange);
      }
    },
    destroy: function destroy() {
      var swiper = this;
      var window = getWindow();

      if (swiper.params.hashNavigation.watchState) {
        $(window).off('hashchange', swiper.hashNavigation.onHashChange);
      }
    }
  };
  var HashNavigation$1 = {
    name: 'hash-navigation',
    params: {
      hashNavigation: {
        enabled: false,
        replaceState: false,
        watchState: false
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        hashNavigation: _extends({
          initialized: false
        }, HashNavigation)
      });
    },
    on: {
      init: function init(swiper) {
        if (swiper.params.hashNavigation.enabled) {
          swiper.hashNavigation.init();
        }
      },
      destroy: function destroy(swiper) {
        if (swiper.params.hashNavigation.enabled) {
          swiper.hashNavigation.destroy();
        }
      },
      'transitionEnd _freeModeNoMomentumRelease': function transitionEnd_freeModeNoMomentumRelease(swiper) {
        if (swiper.hashNavigation.initialized) {
          swiper.hashNavigation.setHash();
        }
      },
      slideChange: function slideChange(swiper) {
        if (swiper.hashNavigation.initialized && swiper.params.cssMode) {
          swiper.hashNavigation.setHash();
        }
      }
    }
  };
  var Autoplay = {
    run: function run() {
      var swiper = this;
      var $activeSlideEl = swiper.slides.eq(swiper.activeIndex);
      var delay = swiper.params.autoplay.delay;

      if ($activeSlideEl.attr('data-swiper-autoplay')) {
        delay = $activeSlideEl.attr('data-swiper-autoplay') || swiper.params.autoplay.delay;
      }

      clearTimeout(swiper.autoplay.timeout);
      swiper.autoplay.timeout = nextTick(function () {
        var autoplayResult;

        if (swiper.params.autoplay.reverseDirection) {
          if (swiper.params.loop) {
            swiper.loopFix();
            autoplayResult = swiper.slidePrev(swiper.params.speed, true, true);
            swiper.emit('autoplay');
          } else if (!swiper.isBeginning) {
            autoplayResult = swiper.slidePrev(swiper.params.speed, true, true);
            swiper.emit('autoplay');
          } else if (!swiper.params.autoplay.stopOnLastSlide) {
            autoplayResult = swiper.slideTo(swiper.slides.length - 1, swiper.params.speed, true, true);
            swiper.emit('autoplay');
          } else {
            swiper.autoplay.stop();
          }
        } else if (swiper.params.loop) {
          swiper.loopFix();
          autoplayResult = swiper.slideNext(swiper.params.speed, true, true);
          swiper.emit('autoplay');
        } else if (!swiper.isEnd) {
          autoplayResult = swiper.slideNext(swiper.params.speed, true, true);
          swiper.emit('autoplay');
        } else if (!swiper.params.autoplay.stopOnLastSlide) {
          autoplayResult = swiper.slideTo(0, swiper.params.speed, true, true);
          swiper.emit('autoplay');
        } else {
          swiper.autoplay.stop();
        }

        if (swiper.params.cssMode && swiper.autoplay.running) swiper.autoplay.run();else if (autoplayResult === false) {
          swiper.autoplay.run();
        }
      }, delay);
    },
    start: function start() {
      var swiper = this;
      if (typeof swiper.autoplay.timeout !== 'undefined') return false;
      if (swiper.autoplay.running) return false;
      swiper.autoplay.running = true;
      swiper.emit('autoplayStart');
      swiper.autoplay.run();
      return true;
    },
    stop: function stop() {
      var swiper = this;
      if (!swiper.autoplay.running) return false;
      if (typeof swiper.autoplay.timeout === 'undefined') return false;

      if (swiper.autoplay.timeout) {
        clearTimeout(swiper.autoplay.timeout);
        swiper.autoplay.timeout = undefined;
      }

      swiper.autoplay.running = false;
      swiper.emit('autoplayStop');
      return true;
    },
    pause: function pause(speed) {
      var swiper = this;
      if (!swiper.autoplay.running) return;
      if (swiper.autoplay.paused) return;
      if (swiper.autoplay.timeout) clearTimeout(swiper.autoplay.timeout);
      swiper.autoplay.paused = true;

      if (speed === 0 || !swiper.params.autoplay.waitForTransition) {
        swiper.autoplay.paused = false;
        swiper.autoplay.run();
      } else {
        ['transitionend', 'webkitTransitionEnd'].forEach(function (event) {
          swiper.$wrapperEl[0].addEventListener(event, swiper.autoplay.onTransitionEnd);
        });
      }
    },
    onVisibilityChange: function onVisibilityChange() {
      var swiper = this;
      var document = getDocument();

      if (document.visibilityState === 'hidden' && swiper.autoplay.running) {
        swiper.autoplay.pause();
      }

      if (document.visibilityState === 'visible' && swiper.autoplay.paused) {
        swiper.autoplay.run();
        swiper.autoplay.paused = false;
      }
    },
    onTransitionEnd: function onTransitionEnd(e) {
      var swiper = this;
      if (!swiper || swiper.destroyed || !swiper.$wrapperEl) return;
      if (e.target !== swiper.$wrapperEl[0]) return;
      ['transitionend', 'webkitTransitionEnd'].forEach(function (event) {
        swiper.$wrapperEl[0].removeEventListener(event, swiper.autoplay.onTransitionEnd);
      });
      swiper.autoplay.paused = false;

      if (!swiper.autoplay.running) {
        swiper.autoplay.stop();
      } else {
        swiper.autoplay.run();
      }
    },
    onMouseEnter: function onMouseEnter() {
      var swiper = this;

      if (swiper.params.autoplay.disableOnInteraction) {
        swiper.autoplay.stop();
      } else {
        swiper.autoplay.pause();
      }

      ['transitionend', 'webkitTransitionEnd'].forEach(function (event) {
        swiper.$wrapperEl[0].removeEventListener(event, swiper.autoplay.onTransitionEnd);
      });
    },
    onMouseLeave: function onMouseLeave() {
      var swiper = this;

      if (swiper.params.autoplay.disableOnInteraction) {
        return;
      }

      swiper.autoplay.paused = false;
      swiper.autoplay.run();
    },
    attachMouseEvents: function attachMouseEvents() {
      var swiper = this;

      if (swiper.params.autoplay.pauseOnMouseEnter) {
        swiper.$el.on('mouseenter', swiper.autoplay.onMouseEnter);
        swiper.$el.on('mouseleave', swiper.autoplay.onMouseLeave);
      }
    },
    detachMouseEvents: function detachMouseEvents() {
      var swiper = this;
      swiper.$el.off('mouseenter', swiper.autoplay.onMouseEnter);
      swiper.$el.off('mouseleave', swiper.autoplay.onMouseLeave);
    }
  };
  var Autoplay$1 = {
    name: 'autoplay',
    params: {
      autoplay: {
        enabled: false,
        delay: 3000,
        waitForTransition: true,
        disableOnInteraction: true,
        stopOnLastSlide: false,
        reverseDirection: false,
        pauseOnMouseEnter: false
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        autoplay: _extends({}, Autoplay, {
          running: false,
          paused: false
        })
      });
    },
    on: {
      init: function init(swiper) {
        if (swiper.params.autoplay.enabled) {
          swiper.autoplay.start();
          var document = getDocument();
          document.addEventListener('visibilitychange', swiper.autoplay.onVisibilityChange);
          swiper.autoplay.attachMouseEvents();
        }
      },
      beforeTransitionStart: function beforeTransitionStart(swiper, speed, internal) {
        if (swiper.autoplay.running) {
          if (internal || !swiper.params.autoplay.disableOnInteraction) {
            swiper.autoplay.pause(speed);
          } else {
            swiper.autoplay.stop();
          }
        }
      },
      sliderFirstMove: function sliderFirstMove(swiper) {
        if (swiper.autoplay.running) {
          if (swiper.params.autoplay.disableOnInteraction) {
            swiper.autoplay.stop();
          } else {
            swiper.autoplay.pause();
          }
        }
      },
      touchEnd: function touchEnd(swiper) {
        if (swiper.params.cssMode && swiper.autoplay.paused && !swiper.params.autoplay.disableOnInteraction) {
          swiper.autoplay.run();
        }
      },
      destroy: function destroy(swiper) {
        swiper.autoplay.detachMouseEvents();

        if (swiper.autoplay.running) {
          swiper.autoplay.stop();
        }

        var document = getDocument();
        document.removeEventListener('visibilitychange', swiper.autoplay.onVisibilityChange);
      }
    }
  };
  var Fade = {
    setTranslate: function setTranslate() {
      var swiper = this;
      var slides = swiper.slides;

      for (var i = 0; i < slides.length; i += 1) {
        var $slideEl = swiper.slides.eq(i);
        var offset = $slideEl[0].swiperSlideOffset;
        var tx = -offset;
        if (!swiper.params.virtualTranslate) tx -= swiper.translate;
        var ty = 0;

        if (!swiper.isHorizontal()) {
          ty = tx;
          tx = 0;
        }

        var slideOpacity = swiper.params.fadeEffect.crossFade ? Math.max(1 - Math.abs($slideEl[0].progress), 0) : 1 + Math.min(Math.max($slideEl[0].progress, -1), 0);
        $slideEl.css({
          opacity: slideOpacity
        }).transform("translate3d(" + tx + "px, " + ty + "px, 0px)");
      }
    },
    setTransition: function setTransition(duration) {
      var swiper = this;
      var slides = swiper.slides,
          $wrapperEl = swiper.$wrapperEl;
      slides.transition(duration);

      if (swiper.params.virtualTranslate && duration !== 0) {
        var eventTriggered = false;
        slides.transitionEnd(function () {
          if (eventTriggered) return;
          if (!swiper || swiper.destroyed) return;
          eventTriggered = true;
          swiper.animating = false;
          var triggerEvents = ['webkitTransitionEnd', 'transitionend'];

          for (var i = 0; i < triggerEvents.length; i += 1) {
            $wrapperEl.trigger(triggerEvents[i]);
          }
        });
      }
    }
  };
  var EffectFade = {
    name: 'effect-fade',
    params: {
      fadeEffect: {
        crossFade: false
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        fadeEffect: _extends({}, Fade)
      });
    },
    on: {
      beforeInit: function beforeInit(swiper) {
        if (swiper.params.effect !== 'fade') return;
        swiper.classNames.push(swiper.params.containerModifierClass + "fade");
        var overwriteParams = {
          slidesPerView: 1,
          slidesPerColumn: 1,
          slidesPerGroup: 1,
          watchSlidesProgress: true,
          spaceBetween: 0,
          virtualTranslate: true
        };
        extend(swiper.params, overwriteParams);
        extend(swiper.originalParams, overwriteParams);
      },
      setTranslate: function setTranslate(swiper) {
        if (swiper.params.effect !== 'fade') return;
        swiper.fadeEffect.setTranslate();
      },
      setTransition: function setTransition(swiper, duration) {
        if (swiper.params.effect !== 'fade') return;
        swiper.fadeEffect.setTransition(duration);
      }
    }
  };
  var Cube = {
    setTranslate: function setTranslate() {
      var swiper = this;
      var $el = swiper.$el,
          $wrapperEl = swiper.$wrapperEl,
          slides = swiper.slides,
          swiperWidth = swiper.width,
          swiperHeight = swiper.height,
          rtl = swiper.rtlTranslate,
          swiperSize = swiper.size,
          browser = swiper.browser;
      var params = swiper.params.cubeEffect;
      var isHorizontal = swiper.isHorizontal();
      var isVirtual = swiper.virtual && swiper.params.virtual.enabled;
      var wrapperRotate = 0;
      var $cubeShadowEl;

      if (params.shadow) {
        if (isHorizontal) {
          $cubeShadowEl = $wrapperEl.find('.swiper-cube-shadow');

          if ($cubeShadowEl.length === 0) {
            $cubeShadowEl = $('<div class="swiper-cube-shadow"></div>');
            $wrapperEl.append($cubeShadowEl);
          }

          $cubeShadowEl.css({
            height: swiperWidth + "px"
          });
        } else {
          $cubeShadowEl = $el.find('.swiper-cube-shadow');

          if ($cubeShadowEl.length === 0) {
            $cubeShadowEl = $('<div class="swiper-cube-shadow"></div>');
            $el.append($cubeShadowEl);
          }
        }
      }

      for (var i = 0; i < slides.length; i += 1) {
        var $slideEl = slides.eq(i);
        var slideIndex = i;

        if (isVirtual) {
          slideIndex = parseInt($slideEl.attr('data-swiper-slide-index'), 10);
        }

        var slideAngle = slideIndex * 90;
        var round = Math.floor(slideAngle / 360);

        if (rtl) {
          slideAngle = -slideAngle;
          round = Math.floor(-slideAngle / 360);
        }

        var progress = Math.max(Math.min($slideEl[0].progress, 1), -1);
        var tx = 0;
        var ty = 0;
        var tz = 0;

        if (slideIndex % 4 === 0) {
          tx = -round * 4 * swiperSize;
          tz = 0;
        } else if ((slideIndex - 1) % 4 === 0) {
          tx = 0;
          tz = -round * 4 * swiperSize;
        } else if ((slideIndex - 2) % 4 === 0) {
          tx = swiperSize + round * 4 * swiperSize;
          tz = swiperSize;
        } else if ((slideIndex - 3) % 4 === 0) {
          tx = -swiperSize;
          tz = 3 * swiperSize + swiperSize * 4 * round;
        }

        if (rtl) {
          tx = -tx;
        }

        if (!isHorizontal) {
          ty = tx;
          tx = 0;
        }

        var transform = "rotateX(" + (isHorizontal ? 0 : -slideAngle) + "deg) rotateY(" + (isHorizontal ? slideAngle : 0) + "deg) translate3d(" + tx + "px, " + ty + "px, " + tz + "px)";

        if (progress <= 1 && progress > -1) {
          wrapperRotate = slideIndex * 90 + progress * 90;
          if (rtl) wrapperRotate = -slideIndex * 90 - progress * 90;
        }

        $slideEl.transform(transform);

        if (params.slideShadows) {
          // Set shadows
          var shadowBefore = isHorizontal ? $slideEl.find('.swiper-slide-shadow-left') : $slideEl.find('.swiper-slide-shadow-top');
          var shadowAfter = isHorizontal ? $slideEl.find('.swiper-slide-shadow-right') : $slideEl.find('.swiper-slide-shadow-bottom');

          if (shadowBefore.length === 0) {
            shadowBefore = $("<div class=\"swiper-slide-shadow-" + (isHorizontal ? 'left' : 'top') + "\"></div>");
            $slideEl.append(shadowBefore);
          }

          if (shadowAfter.length === 0) {
            shadowAfter = $("<div class=\"swiper-slide-shadow-" + (isHorizontal ? 'right' : 'bottom') + "\"></div>");
            $slideEl.append(shadowAfter);
          }

          if (shadowBefore.length) shadowBefore[0].style.opacity = Math.max(-progress, 0);
          if (shadowAfter.length) shadowAfter[0].style.opacity = Math.max(progress, 0);
        }
      }

      $wrapperEl.css({
        '-webkit-transform-origin': "50% 50% -" + swiperSize / 2 + "px",
        '-moz-transform-origin': "50% 50% -" + swiperSize / 2 + "px",
        '-ms-transform-origin': "50% 50% -" + swiperSize / 2 + "px",
        'transform-origin': "50% 50% -" + swiperSize / 2 + "px"
      });

      if (params.shadow) {
        if (isHorizontal) {
          $cubeShadowEl.transform("translate3d(0px, " + (swiperWidth / 2 + params.shadowOffset) + "px, " + -swiperWidth / 2 + "px) rotateX(90deg) rotateZ(0deg) scale(" + params.shadowScale + ")");
        } else {
          var shadowAngle = Math.abs(wrapperRotate) - Math.floor(Math.abs(wrapperRotate) / 90) * 90;
          var multiplier = 1.5 - (Math.sin(shadowAngle * 2 * Math.PI / 360) / 2 + Math.cos(shadowAngle * 2 * Math.PI / 360) / 2);
          var scale1 = params.shadowScale;
          var scale2 = params.shadowScale / multiplier;
          var offset = params.shadowOffset;
          $cubeShadowEl.transform("scale3d(" + scale1 + ", 1, " + scale2 + ") translate3d(0px, " + (swiperHeight / 2 + offset) + "px, " + -swiperHeight / 2 / scale2 + "px) rotateX(-90deg)");
        }
      }

      var zFactor = browser.isSafari || browser.isWebView ? -swiperSize / 2 : 0;
      $wrapperEl.transform("translate3d(0px,0," + zFactor + "px) rotateX(" + (swiper.isHorizontal() ? 0 : wrapperRotate) + "deg) rotateY(" + (swiper.isHorizontal() ? -wrapperRotate : 0) + "deg)");
    },
    setTransition: function setTransition(duration) {
      var swiper = this;
      var $el = swiper.$el,
          slides = swiper.slides;
      slides.transition(duration).find('.swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left').transition(duration);

      if (swiper.params.cubeEffect.shadow && !swiper.isHorizontal()) {
        $el.find('.swiper-cube-shadow').transition(duration);
      }
    }
  };
  var EffectCube = {
    name: 'effect-cube',
    params: {
      cubeEffect: {
        slideShadows: true,
        shadow: true,
        shadowOffset: 20,
        shadowScale: 0.94
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        cubeEffect: _extends({}, Cube)
      });
    },
    on: {
      beforeInit: function beforeInit(swiper) {
        if (swiper.params.effect !== 'cube') return;
        swiper.classNames.push(swiper.params.containerModifierClass + "cube");
        swiper.classNames.push(swiper.params.containerModifierClass + "3d");
        var overwriteParams = {
          slidesPerView: 1,
          slidesPerColumn: 1,
          slidesPerGroup: 1,
          watchSlidesProgress: true,
          resistanceRatio: 0,
          spaceBetween: 0,
          centeredSlides: false,
          virtualTranslate: true
        };
        extend(swiper.params, overwriteParams);
        extend(swiper.originalParams, overwriteParams);
      },
      setTranslate: function setTranslate(swiper) {
        if (swiper.params.effect !== 'cube') return;
        swiper.cubeEffect.setTranslate();
      },
      setTransition: function setTransition(swiper, duration) {
        if (swiper.params.effect !== 'cube') return;
        swiper.cubeEffect.setTransition(duration);
      }
    }
  };
  var Flip = {
    setTranslate: function setTranslate() {
      var swiper = this;
      var slides = swiper.slides,
          rtl = swiper.rtlTranslate;

      for (var i = 0; i < slides.length; i += 1) {
        var $slideEl = slides.eq(i);
        var progress = $slideEl[0].progress;

        if (swiper.params.flipEffect.limitRotation) {
          progress = Math.max(Math.min($slideEl[0].progress, 1), -1);
        }

        var offset = $slideEl[0].swiperSlideOffset;
        var rotate = -180 * progress;
        var rotateY = rotate;
        var rotateX = 0;
        var tx = -offset;
        var ty = 0;

        if (!swiper.isHorizontal()) {
          ty = tx;
          tx = 0;
          rotateX = -rotateY;
          rotateY = 0;
        } else if (rtl) {
          rotateY = -rotateY;
        }

        $slideEl[0].style.zIndex = -Math.abs(Math.round(progress)) + slides.length;

        if (swiper.params.flipEffect.slideShadows) {
          // Set shadows
          var shadowBefore = swiper.isHorizontal() ? $slideEl.find('.swiper-slide-shadow-left') : $slideEl.find('.swiper-slide-shadow-top');
          var shadowAfter = swiper.isHorizontal() ? $slideEl.find('.swiper-slide-shadow-right') : $slideEl.find('.swiper-slide-shadow-bottom');

          if (shadowBefore.length === 0) {
            shadowBefore = $("<div class=\"swiper-slide-shadow-" + (swiper.isHorizontal() ? 'left' : 'top') + "\"></div>");
            $slideEl.append(shadowBefore);
          }

          if (shadowAfter.length === 0) {
            shadowAfter = $("<div class=\"swiper-slide-shadow-" + (swiper.isHorizontal() ? 'right' : 'bottom') + "\"></div>");
            $slideEl.append(shadowAfter);
          }

          if (shadowBefore.length) shadowBefore[0].style.opacity = Math.max(-progress, 0);
          if (shadowAfter.length) shadowAfter[0].style.opacity = Math.max(progress, 0);
        }

        $slideEl.transform("translate3d(" + tx + "px, " + ty + "px, 0px) rotateX(" + rotateX + "deg) rotateY(" + rotateY + "deg)");
      }
    },
    setTransition: function setTransition(duration) {
      var swiper = this;
      var slides = swiper.slides,
          activeIndex = swiper.activeIndex,
          $wrapperEl = swiper.$wrapperEl;
      slides.transition(duration).find('.swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left').transition(duration);

      if (swiper.params.virtualTranslate && duration !== 0) {
        var eventTriggered = false; // eslint-disable-next-line

        slides.eq(activeIndex).transitionEnd(function onTransitionEnd() {
          if (eventTriggered) return;
          if (!swiper || swiper.destroyed) return; // if (!$(this).hasClass(swiper.params.slideActiveClass)) return;

          eventTriggered = true;
          swiper.animating = false;
          var triggerEvents = ['webkitTransitionEnd', 'transitionend'];

          for (var i = 0; i < triggerEvents.length; i += 1) {
            $wrapperEl.trigger(triggerEvents[i]);
          }
        });
      }
    }
  };
  var EffectFlip = {
    name: 'effect-flip',
    params: {
      flipEffect: {
        slideShadows: true,
        limitRotation: true
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        flipEffect: _extends({}, Flip)
      });
    },
    on: {
      beforeInit: function beforeInit(swiper) {
        if (swiper.params.effect !== 'flip') return;
        swiper.classNames.push(swiper.params.containerModifierClass + "flip");
        swiper.classNames.push(swiper.params.containerModifierClass + "3d");
        var overwriteParams = {
          slidesPerView: 1,
          slidesPerColumn: 1,
          slidesPerGroup: 1,
          watchSlidesProgress: true,
          spaceBetween: 0,
          virtualTranslate: true
        };
        extend(swiper.params, overwriteParams);
        extend(swiper.originalParams, overwriteParams);
      },
      setTranslate: function setTranslate(swiper) {
        if (swiper.params.effect !== 'flip') return;
        swiper.flipEffect.setTranslate();
      },
      setTransition: function setTransition(swiper, duration) {
        if (swiper.params.effect !== 'flip') return;
        swiper.flipEffect.setTransition(duration);
      }
    }
  };
  var Coverflow = {
    setTranslate: function setTranslate() {
      var swiper = this;
      var swiperWidth = swiper.width,
          swiperHeight = swiper.height,
          slides = swiper.slides,
          slidesSizesGrid = swiper.slidesSizesGrid;
      var params = swiper.params.coverflowEffect;
      var isHorizontal = swiper.isHorizontal();
      var transform = swiper.translate;
      var center = isHorizontal ? -transform + swiperWidth / 2 : -transform + swiperHeight / 2;
      var rotate = isHorizontal ? params.rotate : -params.rotate;
      var translate = params.depth; // Each slide offset from center

      for (var i = 0, length = slides.length; i < length; i += 1) {
        var $slideEl = slides.eq(i);
        var slideSize = slidesSizesGrid[i];
        var slideOffset = $slideEl[0].swiperSlideOffset;
        var offsetMultiplier = (center - slideOffset - slideSize / 2) / slideSize * params.modifier;
        var rotateY = isHorizontal ? rotate * offsetMultiplier : 0;
        var rotateX = isHorizontal ? 0 : rotate * offsetMultiplier; // var rotateZ = 0

        var translateZ = -translate * Math.abs(offsetMultiplier);
        var stretch = params.stretch; // Allow percentage to make a relative stretch for responsive sliders

        if (typeof stretch === 'string' && stretch.indexOf('%') !== -1) {
          stretch = parseFloat(params.stretch) / 100 * slideSize;
        }

        var translateY = isHorizontal ? 0 : stretch * offsetMultiplier;
        var translateX = isHorizontal ? stretch * offsetMultiplier : 0;
        var scale = 1 - (1 - params.scale) * Math.abs(offsetMultiplier); // Fix for ultra small values

        if (Math.abs(translateX) < 0.001) translateX = 0;
        if (Math.abs(translateY) < 0.001) translateY = 0;
        if (Math.abs(translateZ) < 0.001) translateZ = 0;
        if (Math.abs(rotateY) < 0.001) rotateY = 0;
        if (Math.abs(rotateX) < 0.001) rotateX = 0;
        if (Math.abs(scale) < 0.001) scale = 0;
        var slideTransform = "translate3d(" + translateX + "px," + translateY + "px," + translateZ + "px)  rotateX(" + rotateX + "deg) rotateY(" + rotateY + "deg) scale(" + scale + ")";
        $slideEl.transform(slideTransform);
        $slideEl[0].style.zIndex = -Math.abs(Math.round(offsetMultiplier)) + 1;

        if (params.slideShadows) {
          // Set shadows
          var $shadowBeforeEl = isHorizontal ? $slideEl.find('.swiper-slide-shadow-left') : $slideEl.find('.swiper-slide-shadow-top');
          var $shadowAfterEl = isHorizontal ? $slideEl.find('.swiper-slide-shadow-right') : $slideEl.find('.swiper-slide-shadow-bottom');

          if ($shadowBeforeEl.length === 0) {
            $shadowBeforeEl = $("<div class=\"swiper-slide-shadow-" + (isHorizontal ? 'left' : 'top') + "\"></div>");
            $slideEl.append($shadowBeforeEl);
          }

          if ($shadowAfterEl.length === 0) {
            $shadowAfterEl = $("<div class=\"swiper-slide-shadow-" + (isHorizontal ? 'right' : 'bottom') + "\"></div>");
            $slideEl.append($shadowAfterEl);
          }

          if ($shadowBeforeEl.length) $shadowBeforeEl[0].style.opacity = offsetMultiplier > 0 ? offsetMultiplier : 0;
          if ($shadowAfterEl.length) $shadowAfterEl[0].style.opacity = -offsetMultiplier > 0 ? -offsetMultiplier : 0;
        }
      }
    },
    setTransition: function setTransition(duration) {
      var swiper = this;
      swiper.slides.transition(duration).find('.swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left').transition(duration);
    }
  };
  var EffectCoverflow = {
    name: 'effect-coverflow',
    params: {
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        scale: 1,
        modifier: 1,
        slideShadows: true
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        coverflowEffect: _extends({}, Coverflow)
      });
    },
    on: {
      beforeInit: function beforeInit(swiper) {
        if (swiper.params.effect !== 'coverflow') return;
        swiper.classNames.push(swiper.params.containerModifierClass + "coverflow");
        swiper.classNames.push(swiper.params.containerModifierClass + "3d");
        swiper.params.watchSlidesProgress = true;
        swiper.originalParams.watchSlidesProgress = true;
      },
      setTranslate: function setTranslate(swiper) {
        if (swiper.params.effect !== 'coverflow') return;
        swiper.coverflowEffect.setTranslate();
      },
      setTransition: function setTransition(swiper, duration) {
        if (swiper.params.effect !== 'coverflow') return;
        swiper.coverflowEffect.setTransition(duration);
      }
    }
  };
  var Thumbs = {
    init: function init() {
      var swiper = this;
      var thumbsParams = swiper.params.thumbs;
      if (swiper.thumbs.initialized) return false;
      swiper.thumbs.initialized = true;
      var SwiperClass = swiper.constructor;

      if (thumbsParams.swiper instanceof SwiperClass) {
        swiper.thumbs.swiper = thumbsParams.swiper;
        extend(swiper.thumbs.swiper.originalParams, {
          watchSlidesProgress: true,
          slideToClickedSlide: false
        });
        extend(swiper.thumbs.swiper.params, {
          watchSlidesProgress: true,
          slideToClickedSlide: false
        });
      } else if (isObject(thumbsParams.swiper)) {
        swiper.thumbs.swiper = new SwiperClass(extend({}, thumbsParams.swiper, {
          watchSlidesVisibility: true,
          watchSlidesProgress: true,
          slideToClickedSlide: false
        }));
        swiper.thumbs.swiperCreated = true;
      }

      swiper.thumbs.swiper.$el.addClass(swiper.params.thumbs.thumbsContainerClass);
      swiper.thumbs.swiper.on('tap', swiper.thumbs.onThumbClick);
      return true;
    },
    onThumbClick: function onThumbClick() {
      var swiper = this;
      var thumbsSwiper = swiper.thumbs.swiper;
      if (!thumbsSwiper) return;
      var clickedIndex = thumbsSwiper.clickedIndex;
      var clickedSlide = thumbsSwiper.clickedSlide;
      if (clickedSlide && $(clickedSlide).hasClass(swiper.params.thumbs.slideThumbActiveClass)) return;
      if (typeof clickedIndex === 'undefined' || clickedIndex === null) return;
      var slideToIndex;

      if (thumbsSwiper.params.loop) {
        slideToIndex = parseInt($(thumbsSwiper.clickedSlide).attr('data-swiper-slide-index'), 10);
      } else {
        slideToIndex = clickedIndex;
      }

      if (swiper.params.loop) {
        var currentIndex = swiper.activeIndex;

        if (swiper.slides.eq(currentIndex).hasClass(swiper.params.slideDuplicateClass)) {
          swiper.loopFix(); // eslint-disable-next-line

          swiper._clientLeft = swiper.$wrapperEl[0].clientLeft;
          currentIndex = swiper.activeIndex;
        }

        var prevIndex = swiper.slides.eq(currentIndex).prevAll("[data-swiper-slide-index=\"" + slideToIndex + "\"]").eq(0).index();
        var nextIndex = swiper.slides.eq(currentIndex).nextAll("[data-swiper-slide-index=\"" + slideToIndex + "\"]").eq(0).index();
        if (typeof prevIndex === 'undefined') slideToIndex = nextIndex;else if (typeof nextIndex === 'undefined') slideToIndex = prevIndex;else if (nextIndex - currentIndex < currentIndex - prevIndex) slideToIndex = nextIndex;else slideToIndex = prevIndex;
      }

      swiper.slideTo(slideToIndex);
    },
    update: function update(initial) {
      var swiper = this;
      var thumbsSwiper = swiper.thumbs.swiper;
      if (!thumbsSwiper) return;
      var slidesPerView = thumbsSwiper.params.slidesPerView === 'auto' ? thumbsSwiper.slidesPerViewDynamic() : thumbsSwiper.params.slidesPerView;
      var autoScrollOffset = swiper.params.thumbs.autoScrollOffset;
      var useOffset = autoScrollOffset && !thumbsSwiper.params.loop;

      if (swiper.realIndex !== thumbsSwiper.realIndex || useOffset) {
        var currentThumbsIndex = thumbsSwiper.activeIndex;
        var newThumbsIndex;
        var direction;

        if (thumbsSwiper.params.loop) {
          if (thumbsSwiper.slides.eq(currentThumbsIndex).hasClass(thumbsSwiper.params.slideDuplicateClass)) {
            thumbsSwiper.loopFix(); // eslint-disable-next-line

            thumbsSwiper._clientLeft = thumbsSwiper.$wrapperEl[0].clientLeft;
            currentThumbsIndex = thumbsSwiper.activeIndex;
          } // Find actual thumbs index to slide to


          var prevThumbsIndex = thumbsSwiper.slides.eq(currentThumbsIndex).prevAll("[data-swiper-slide-index=\"" + swiper.realIndex + "\"]").eq(0).index();
          var nextThumbsIndex = thumbsSwiper.slides.eq(currentThumbsIndex).nextAll("[data-swiper-slide-index=\"" + swiper.realIndex + "\"]").eq(0).index();

          if (typeof prevThumbsIndex === 'undefined') {
            newThumbsIndex = nextThumbsIndex;
          } else if (typeof nextThumbsIndex === 'undefined') {
            newThumbsIndex = prevThumbsIndex;
          } else if (nextThumbsIndex - currentThumbsIndex === currentThumbsIndex - prevThumbsIndex) {
            newThumbsIndex = thumbsSwiper.params.slidesPerGroup > 1 ? nextThumbsIndex : currentThumbsIndex;
          } else if (nextThumbsIndex - currentThumbsIndex < currentThumbsIndex - prevThumbsIndex) {
            newThumbsIndex = nextThumbsIndex;
          } else {
            newThumbsIndex = prevThumbsIndex;
          }

          direction = swiper.activeIndex > swiper.previousIndex ? 'next' : 'prev';
        } else {
          newThumbsIndex = swiper.realIndex;
          direction = newThumbsIndex > swiper.previousIndex ? 'next' : 'prev';
        }

        if (useOffset) {
          newThumbsIndex += direction === 'next' ? autoScrollOffset : -1 * autoScrollOffset;
        }

        if (thumbsSwiper.visibleSlidesIndexes && thumbsSwiper.visibleSlidesIndexes.indexOf(newThumbsIndex) < 0) {
          if (thumbsSwiper.params.centeredSlides) {
            if (newThumbsIndex > currentThumbsIndex) {
              newThumbsIndex = newThumbsIndex - Math.floor(slidesPerView / 2) + 1;
            } else {
              newThumbsIndex = newThumbsIndex + Math.floor(slidesPerView / 2) - 1;
            }
          } else if (newThumbsIndex > currentThumbsIndex && thumbsSwiper.params.slidesPerGroup === 1) ;

          thumbsSwiper.slideTo(newThumbsIndex, initial ? 0 : undefined);
        }
      } // Activate thumbs


      var thumbsToActivate = 1;
      var thumbActiveClass = swiper.params.thumbs.slideThumbActiveClass;

      if (swiper.params.slidesPerView > 1 && !swiper.params.centeredSlides) {
        thumbsToActivate = swiper.params.slidesPerView;
      }

      if (!swiper.params.thumbs.multipleActiveThumbs) {
        thumbsToActivate = 1;
      }

      thumbsToActivate = Math.floor(thumbsToActivate);
      thumbsSwiper.slides.removeClass(thumbActiveClass);

      if (thumbsSwiper.params.loop || thumbsSwiper.params.virtual && thumbsSwiper.params.virtual.enabled) {
        for (var i = 0; i < thumbsToActivate; i += 1) {
          thumbsSwiper.$wrapperEl.children("[data-swiper-slide-index=\"" + (swiper.realIndex + i) + "\"]").addClass(thumbActiveClass);
        }
      } else {
        for (var _i = 0; _i < thumbsToActivate; _i += 1) {
          thumbsSwiper.slides.eq(swiper.realIndex + _i).addClass(thumbActiveClass);
        }
      }
    }
  };
  var Thumbs$1 = {
    name: 'thumbs',
    params: {
      thumbs: {
        swiper: null,
        multipleActiveThumbs: true,
        autoScrollOffset: 0,
        slideThumbActiveClass: 'swiper-slide-thumb-active',
        thumbsContainerClass: 'swiper-container-thumbs'
      }
    },
    create: function create() {
      var swiper = this;
      bindModuleMethods(swiper, {
        thumbs: _extends({
          swiper: null,
          initialized: false
        }, Thumbs)
      });
    },
    on: {
      beforeInit: function beforeInit(swiper) {
        var thumbs = swiper.params.thumbs;
        if (!thumbs || !thumbs.swiper) return;
        swiper.thumbs.init();
        swiper.thumbs.update(true);
      },
      slideChange: function slideChange(swiper) {
        if (!swiper.thumbs.swiper) return;
        swiper.thumbs.update();
      },
      update: function update(swiper) {
        if (!swiper.thumbs.swiper) return;
        swiper.thumbs.update();
      },
      resize: function resize(swiper) {
        if (!swiper.thumbs.swiper) return;
        swiper.thumbs.update();
      },
      observerUpdate: function observerUpdate(swiper) {
        if (!swiper.thumbs.swiper) return;
        swiper.thumbs.update();
      },
      setTransition: function setTransition(swiper, duration) {
        var thumbsSwiper = swiper.thumbs.swiper;
        if (!thumbsSwiper) return;
        thumbsSwiper.setTransition(duration);
      },
      beforeDestroy: function beforeDestroy(swiper) {
        var thumbsSwiper = swiper.thumbs.swiper;
        if (!thumbsSwiper) return;

        if (swiper.thumbs.swiperCreated && thumbsSwiper) {
          thumbsSwiper.destroy();
        }
      }
    }
  }; // Swiper Class

  var components = [Virtual$1, Keyboard$1, Mousewheel$1, Navigation$1, Pagination$1, Scrollbar$1, Parallax$1, Zoom$1, Lazy$1, Controller$1, A11y$1, History$1, HashNavigation$1, Autoplay$1, EffectFade, EffectCube, EffectFlip, EffectCoverflow, Thumbs$1];
  Swiper.use(components);
  return Swiper;
});
"use strict";

var notesSlider = new Swiper('#notes-slider', {
  slidesPerView: "auto",
  centeredSlides: true,
  loop: true,
  pagination: {
    el: ".notes-slider_dots",
    bulletActiveClass: "notes-slider_dot--active",
    bulletClass: "notes-slider_dot",
    clickable: true
  },
  navigation: {
    nextEl: '.steps__button-next',
    prevEl: '.steps__button-prev',
  }
});
var storageCostTabs = new Swiper('#storage-cost-tabs', {
  allowTouchMove: false
});
var storageCostSlider = new Swiper('#storage-cost-slider', {
  slidesPerView: 'auto',
  spaceBetween: 90,
  centeredSlides: true,
  slideToClickedSlide: true,
  slideActiveClass: 'storage-cost-slide--active',
  speed: 1000,
  loop: false,
  breakpoints: {
    1025: {
      slidesPerView: 7,
      spaceBetween: 0
    },
    980: {
      slidesPerView: 5,
      spaceBetween: 10
    },
    471: {
      spaceBetween: 40
    }
  },
  navigation: {
    nextEl: '.storage-cost_btn-slide--right',
    prevEl: '.storage-cost_btn-slide--left'
  },
  thumbs: {
    swiper: storageCostTabs,
    slideThumbActiveClass: 'storage-cost-tab--active'
  }
});

var costButtons = $(document).find('.js-storage-cost-tab');
if (costButtons.length) {
  costButtons.map((item) => {
    $(costButtons[item]).on('mousedown', (event) => {
      var parent = $(event.target).closest('.js-storage-cost-tab');
      if (parent.length) {
        storageCostSlider.slideTo($(parent).index(), 1000);
      }
    });
  });
}

var stepsSliderImg1 = new Swiper('#steps_slider-img-1', {
  centeredSlides: true,
  loop: true,
  pagination: {
    el: ".steps_slider-img-1_dots",
    bulletActiveClass: "steps_slider-img-dots_dot--active",
    bulletClass: "steps_slider-img-dots_dot",
    clickable: true
  },
  navigation: {
    nextEl: '.steps__button-next',
    prevEl: '.steps__button-prev',
  }
});
var stepsSliderImg2 = new Swiper('#steps_slider-img-2', {
  centeredSlides: true,
  loop: true,
  pagination: {
    el: ".steps_slider-img-2_dots",
    bulletActiveClass: "steps_slider-img-dots_dot--active",
    bulletClass: "steps_slider-img-dots_dot",
    clickable: true
  },
  navigation: {
    nextEl: '.steps__button-next',
    prevEl: '.steps__button-prev',
  }
});

function lottiSteps() {
  var lottiDesktop = './lotti/Animation_desktop.json';
  var lottiTablet = './lotti/Animation_tablet.json';
  var lottiPhone = './lotti/Animation_phone_fix.json';
  var lotti = lottiDesktop;
  var clientW = document.documentElement.clientWidth;

  if (clientW <= 769 && clientW > 470) {
    lotti = lottiTablet;
  } else if (clientW <= 470) {
    lotti = lottiPhone;
  }

  var stepsBgLotti = document.querySelector('.steps_bg-lotti');
  lottie.destroy();
  lottie.loadAnimation({
    container: stepsBgLotti,
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: lotti
  });
}

window.addEventListener("orientationchange", function () {
  lottiSteps();
});
window.addEventListener("resize", function () {
  lottiSteps();
});
lottiSteps();