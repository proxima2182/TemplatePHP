/**
 * @file login popup 스크립트
 */

/**
 * login 가능한 popup 을 여는 기능
 * 모든 페이지에서 login 버튼을 이용해 호출할 수 있도록 함
 * @returns {Promise<void>}
 * @throws {Response}           fetch 로 파일읽기에 실패했을 경우 결과를 throw
 * @todo throw 에 잡힌 경우 log 로 남길 필요 있음
 */
async function openPopupLogin() {
    try {
        let request = await fetch('/asset/css/common/input.css')
        if (!request.ok) throw request;
        let css = await request.text()
        let className = 'popup-login';
        let style = `
        <style>
        ${css}
        body .${className} .popup {
            width: 600px;
        }

        .${className} h3.title {
            margin: 0 0 20px 0;
            font-size: 20px;
            font-weight: 600;
        }

        .${className} .popup-inner {
            overflow: hidden;
        }

        .${className} .input-wrap .input-title {
            width: calc(30% - 15px);
        }

        .${className} .form-wrap .input-wrap * {
            line-height: 40px;
        }

        .${className} .control-wrap {
            margin-top: 10px;
            line-height: 20px;
            font-weight: 600;
        }

        .${className} .popup-inner .button-wrap {
            margin-top: 40px;
        }

        .${className} .popup-inner .button-wrap .button {
            min-width: 100px;
            padding: 10px 20px;
            margin: 0 10px;
        }
        </style>`
        let html = `
        <h3 class="title">
            Login
        </h3>
        <div class="form-wrap">
            <div class="input-wrap">
                <p class="input-title">Username</p>
                <input type="text" name="username" class="under-line"/>
            </div>
            <div class="input-wrap">
                <p class="input-title">Password</p>
                <input type="password" name="password" class="under-line"/>
            </div>
        </div>
        <div class="control-wrap">
            <a href="/register" class="button register">
                <img src="/asset/images/icon/plus_circle.png"/>
                <span>Register</span>
            </a>
        </div>
        <div class="button-wrap controls">
            <a href="javascript:closePopup('${className}')" class="button cancel white">Cancel</a>
            <a href="javascript:login()" class="button confirm black">Login</a>
        </div>`;
        openPopup(className, style, html)
    } catch (e) {
        console.log(e)
    }
}

function login() {

}
