import os
import jinja2
import functools
import uvicorn
from fastapi import FastAPI
from fastapi.templating import Jinja2Templates
from anyio import fail_after, sleep

def timeout_after(timeout: int = 1):
    def decorator(func):
        @functools.wraps(func)
        async def wrapper(*args, **kwargs):
            with fail_after(timeout):
                return await func(*args, **kwargs)
        return wrapper

    return decorator
    
app = FastAPI()
access = False

_base_path = os.path.dirname(os.path.abspath(__file__))
t = Jinja2Templates(directory=_base_path)

@app.get("/")
@timeout_after(1)
async def index():
    return open(__file__, 'r').read()

@app.get("/calc")
@timeout_after(1)
async def ssti(calc_req: str):
    global access
    if (any(char.isdigit() for char in calc_req)) or ("%" in calc_req) or not calc_req.isascii() or access:
        return "bad char"
    else:
        jinja2.Environment(loader=jinja2.BaseLoader()).from_string(f"{{{{ {calc_req} }}}}").render({"app": app})
        access = True
    return "fight"

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)