defmodule PhoenixApiWeb.Plugs.Authenticate do
  import Plug.Conn
  import Phoenix.Controller
  alias PhoenixApi.Repo
  alias PhoenixApi.Accounts.User

  def init(opts), do: opts

  def call(conn, _opts) do
    case get_req_header(conn, "access-token") do
      [token] ->
        case Repo.get_by(User, api_token: token) do
          %User{} = user ->
            check_rates(user, conn)
            assign(conn, :current_user, user)

          nil ->
            render_unauthorized(conn)
        end

      [] ->
        render_unauthorized(conn)
    end
  end

  defp check_rates(user, conn) do
    interval_milliseconds = 60 * 60 * 1000 #1 hour
    max_requests = 999
    general_bucket_name = "connections_all"
    case ExRated.check_rate(general_bucket_name, interval_milliseconds, max_requests) do
      {:ok, _count} -> true
      {:error, _limit} -> render_rate_limit_reached(conn)
    end

    interval_milliseconds = 10 * 60 * 1000 #10 min
    max_requests = 5
    user_bucket_name = "connections #{user.id}"
    case ExRated.check_rate(user_bucket_name, interval_milliseconds, max_requests) do
      {:ok, _count} -> true
      {:error, _limit} -> render_rate_limit_reached(conn)
    end

  end

  def render_unauthorized(conn) do
    conn
    |> put_status(:unauthorized)
    |> put_view(json: PhoenixApiWeb.ErrorJSON)
    |> render(:"401")
    |> halt()
  end

  def render_rate_limit_reached(conn) do
    conn
    |> put_status(:forbidden)
    |> put_view(json: PhoenixApiWeb.ErrorJSON)
    |> render(:"403")
    |> halt()
  end
end

