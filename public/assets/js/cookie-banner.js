// MIT License

// Copyright (c) 2020 Kolappan Nathan

// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.

function showCookieBanner(){let cookieBanner=document.getElementById("cb-cookie-banner");cookieBanner.style.display="block"}function hideCookieBanner(){localStorage.setItem("cb_isCookieAccepted","yes");let cookieBanner=document.getElementById("cb-cookie-banner");cookieBanner.style.display="none"}function initializeCookieBanner(){let isCookieAccepted=localStorage.getItem("cb_isCookieAccepted");if(isCookieAccepted===null){localStorage.setItem("cb_isCookieAccepted","no");showCookieBanner()}if(isCookieAccepted==="no"){showCookieBanner()}}window.onload=initializeCookieBanner();window.cb_hideCookieBanner=hideCookieBanner;
