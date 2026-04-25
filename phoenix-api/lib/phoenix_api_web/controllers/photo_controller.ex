defmodule PhoenixApiWeb.PhotoController do
  use PhoenixApiWeb, :controller

  alias PhoenixApi.Repo
  alias PhoenixApi.Media.Photo
  import Ecto.Query

  plug PhoenixApiWeb.Plugs.Authenticate

  def index(conn, _params) do
    current_user = conn.assigns.current_user

    photos =
      Photo
      |> where([p], p.user_id == ^current_user.id)
      |> select([p], %{
        id: p.id,
        photo_url: p.photo_url,
        camera: p.camera,
        lens: p.lens,
        settings: p.settings,
        description: p.description,
        location: p.location,
        focal_length: p.focal_length,
        aperture: p.aperture,
        shutter_speed: p.shutter_speed,
        iso: p.iso,
        taken_at: p.taken_at,
        user_id: p.user_id
      })
      |> Repo.all()

    json(conn, %{photos: photos})
  end
end
